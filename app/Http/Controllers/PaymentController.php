<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $razorpayKey;
    private $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = config('razorpay.key_id');
        $this->razorpaySecret = config('razorpay.key_secret');
    }

    /**
     * Show admin payment management page
     */
    public function index()
    {
        $payments = Payment::with('user')->orderBy('id', 'desc')->get();
        return view('admin.payments', compact('payments'));
    }

    /**
     * Package configurations with breakdowns
     */
    private function getPackageDetails($packageType)
    {
        $packages = [
            'basic' => [
                'name' => 'Basic Plan - India Trade Fairs',
                'amount' => 50000,
                'breakdown' => [
                    'Travel' => 20000,
                    'Accommodation' => 15000,
                    'Food' => 8000,
                    'Event Registration' => 5000,
                    'Other Expenses' => 2000,
                ],
                'features' => [
                    'Trade fairs across India',
                    'Domestic flight & accommodation',
                    'Event registration assistance',
                    'Local transport',
                    '24/7 support during trip',
                ]
            ],
            'pro' => [
                'name' => 'Pro Plan - Asia Trade Fairs',
                'amount' => 200000,
                'breakdown' => [
                    'Travel' => 80000,
                    'Accommodation' => 60000,
                    'Food' => 25000,
                    'Event Registration' => 20000,
                    'Coordination' => 10000,
                    'Other Expenses' => 5000,
                ],
                'features' => [
                    'Trade fairs across All Asia',
                    'International flights & 4-star hotels',
                    'VIP event registration & networking',
                    'Premium transport & meals',
                    'Dedicated trip coordinator',
                    'Visa assistance included',
                ]
            ],
            'expert' => [
                'name' => 'Expert Plan - Global Trade Fairs',
                'amount' => 800000,
                'breakdown' => [
                    'Travel' => 350000,
                    'Accommodation' => 250000,
                    'Food' => 80000,
                    'Event Registration' => 60000,
                    'Concierge Service' => 40000,
                    'Other Expenses' => 20000,
                ],
                'features' => [
                    'Trade fairs Worldwide (Europe focus)',
                    'Business class flights & 5-star hotels',
                    'Exclusive VIP access & private meetings',
                    'Luxury transport & fine dining',
                    'Personal concierge service',
                    'Complete visa & documentation support',
                    'Cultural tours & business insights',
                ]
            ]
        ];

        return $packages[$packageType] ?? null;
    }

    /**
     * Show payment details page
     */
    public function showPaymentPage($package)
    {
        $packageDetails = $this->getPackageDetails($package);

        if (!$packageDetails) {
            return redirect()->route('tour.packages')->with('error', 'Invalid package selected');
        }

        return view('payment-details', [
            'packageType' => $package,
            'packageDetails' => $packageDetails,
            'razorpayKey' => $this->razorpayKey,
        ]);
    }

    /**
     * Create Razorpay order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'package_type' => 'required|in:basic,pro,expert',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
        ]);

        $packageDetails = $this->getPackageDetails($request->package_type);

        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);

            // Create Razorpay Order
            $orderData = [
                'receipt' => 'order_' . time(),
                'amount' => $packageDetails['amount'] * 100, // Amount in paise
                'currency' => 'INR',
                'notes' => [
                    'package_type' => $request->package_type,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                ]
            ];

            $razorpayOrder = $api->order->create($orderData);

            // Store payment data in session (NOT in database yet - only after successful payment)
            $paymentData = [
                'user_id' => auth()->id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'package_type' => $request->package_type,
                'package_name' => $packageDetails['name'],
                'amount' => $packageDetails['amount'],
                'breakdown' => $packageDetails['breakdown'],
                'razorpay_order_id' => $razorpayOrder['id'],
            ];
            
            session(['pending_payment_' . $razorpayOrder['id'] => $paymentData]);

            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $packageDetails['amount'],
                'key' => $this->razorpayKey,
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify payment and update status
     */
    public function verifyPayment(Request $request)
    {
        Log::info('Payment Verification Started', $request->all());
        
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);

            // Verify signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            Log::info('Verifying signature', $attributes);
            
            $api->utility->verifyPaymentSignature($attributes);

            Log::info('Signature verified successfully');

            // Get payment data from session
            $paymentData = session('pending_payment_' . $request->razorpay_order_id);
            
            if (!$paymentData) {
                throw new \Exception('Payment data not found in session');
            }

            // NOW save to database (only after successful verification)
            $payment = Payment::create([
                'user_id' => $paymentData['user_id'],
                'name' => $paymentData['name'],
                'email' => $paymentData['email'],
                'phone' => $paymentData['phone'],
                'package_type' => $paymentData['package_type'],
                'package_name' => $paymentData['package_name'],
                'amount' => $paymentData['amount'],
                'breakdown' => $paymentData['breakdown'],
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'status' => 'success',
            ]);

            // Generate and save PDF
            $pdf = \PDF::loadView('payment-receipt-pdf', compact('payment'));
            $pdfFileName = 'payment-receipt-' . $payment->id . '.pdf';
            $pdfPath = 'receipts/' . $pdfFileName;
            
            // Save PDF to storage
            \Storage::put('public/' . $pdfPath, $pdf->output());
            
            // Update payment with PDF path
            $payment->update(['pdf_path' => $pdfPath]);

            // Clear session data
            session()->forget('pending_payment_' . $request->razorpay_order_id);

            Log::info('Payment saved successfully', ['payment_id' => $payment->id]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect_url' => route('payment.success', $payment->id),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Verification Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Update payment as failed
            if (isset($request->payment_id)) {
                $payment = Payment::find($request->payment_id);
                if ($payment) {
                    $payment->update(['status' => 'failed']);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
                'redirect_url' => route('payment.failure'),
            ], 400);
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        return view('payment-success', compact('payment'));
    }

    /**
     * Payment failure page
     */
    public function paymentFailure()
    {
        return view('payment-failure');
    }

    /**
     * Download payment receipt as PDF
     */
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $pdf = \PDF::loadView('payment-receipt-pdf', compact('payment'));
        
        return $pdf->download('payment-receipt-' . $payment->id . '.pdf');
    }
}
