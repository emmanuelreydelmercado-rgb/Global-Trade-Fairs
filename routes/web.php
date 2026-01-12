<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Form;

use App\Http\Controllers\InputBoxController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnalyticsController;

/*
|--------------------------------------------------------------------------
| HOME → Global Fairs Page
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| TOUR PACKAGES
|--------------------------------------------------------------------------
*/
Route::get('/tour-packages', function () {
    return view('tour-packages');
})->name('tour.packages');

/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES (Protected - Login Required)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{package}', [PaymentController::class, 'showPaymentPage'])->name('payment.details');
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/payment/success/{paymentId}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');
    Route::get('/payment/download/{paymentId}', [PaymentController::class, 'downloadReceipt'])->name('payment.download');
    
    
    // Admin payment management
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('admin.payments');

    // Wishlist routes
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/wishlist/check/{formId}', [App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');
});



/*
|--------------------------------------------------------------------------
| EVENT FORM
|--------------------------------------------------------------------------
*/
Route::get('/event-form', [InputBoxController::class, 'viewform'])
    ->name('event.form');

Route::post('/event-form', [InputBoxController::class, 'addtable'])
    ->name('event.submit');

// API endpoint for getting cities by country
Route::get('/get-cities/{country}', [InputBoxController::class, 'getCities'])
    ->name('get.cities');





/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Google OAuth Routes
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');
    
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');



/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // -------------------------
    // ANALYTICS API
    // -------------------------
    Route::get('/api/analytics/dashboard-stats', [AnalyticsController::class, 'getDashboardStats'])
        ->name('analytics.dashboard.stats');

    // -------------------------
    // DASHBOARDS
    // -------------------------
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard.old');

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');


    // -------------------------
    // PROFILE (FIXED & WORKING)
    // -------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    // -------------------------
    // APPROVE / REJECT (SECURED)
    // -------------------------
    Route::get('/approve/{id}', function ($id) {
        Form::where('id', $id)->update(['status' => 'approved']);
        
        // 🚀 Clear map analytics cache when event is approved
        \Cache::forget('map-analytics-data');
        
        return back()->with('success', 'Approved successfully');
    })->name('approve.form');

    Route::get('/reject/{id}', function ($id) {
        Form::where('id', $id)->update(['status' => 'rejected']);
        
        // 🚀 Clear map analytics cache when event is rejected
        \Cache::forget('map-analytics-data');
        
        return back()->with('success', 'Rejected successfully');
    })->name('disapprove.form');

    // -------------------------
    // EDIT / UPDATE FORM
    // -------------------------
    Route::get('/form/{id}/edit', function ($id) {
        $form =Form::findOrFail($id);
        return view('event-form', compact('form'));
    })->name('form.edit');

    Route::put('/form/{id}', function (Request $request, $id) {
        $form = Form::findOrFail($id);
        $form->update($request->only(['Orgname', 'VenueName', 'city', 'country', 'Date', 'ExponName', 'phone', 'email', 'hallno', 'reglink']));
        
        // 🚀 Clear map analytics cache when event is updated
        \Cache::forget('map-analytics-data');
        
        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully');
    })->name('form.update');
});


/*
|--------------------------------------------------------------------------
| FAIR DETAILS
|--------------------------------------------------------------------------
*/
Route::get('/fair/{id}', function ($id) {
    $form = Form::findOrFail($id);
    return view('fair-details', compact('form'));
})->name('fair.details');

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\AdminLoginController;

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD (Email-restricted)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin.email'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');

});

/*
|--------------------------------------------------------------------------
| CHATBOT ROUTES
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ChatbotController;

Route::prefix('chatbot')->group(function () {
    Route::post('/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');
    Route::get('/history', [ChatbotController::class, 'getHistory'])->name('chatbot.history');
    Route::post('/end-session', [ChatbotController::class, 'endSession'])->name('chatbot.end');
    Route::get('/quick-actions', [ChatbotController::class, 'getQuickActions'])->name('chatbot.actions');
});

// DEBUG: Profile picture diagnostic (remove after fixing)
Route::get('/debug-profile', function() {
    return view('debug-profile');
})->middleware('auth');

// DEBUG: Chatbot diagnostic
Route::get('/test-chatbot', function() {
    $apiKey = config('services.gemini.api_key');
    $model = config('services.gemini.model', 'gemini-2.5-flash');
    
    return response()->json([
        'api_key_configured' => !empty($apiKey),
        'api_key_length' => strlen($apiKey ?? ''),
        'api_key_first_10' => !empty($apiKey) ? substr($apiKey, 0, 10) . '...' : 'NOT SET',
        'model' => $model,
        'chatbot_enabled' => config('services.gemini.enabled', true),
        'database' => [
            'conversations_table_exists' => Schema::hasTable('chat_conversations'),
            'messages_table_exists' => Schema::hasTable('chat_messages'),
        ],
        'env_check' => [
            'APP_DEBUG' => config('app.debug'),
            'APP_ENV' => config('app.env'),
        ]
    ]);
});

// DEBUG: Test actual Gemini API call
Route::get('/test-gemini-api', function() {
    try {
        $gemini = app(\App\Services\GeminiService::class);
        $response = $gemini->generateResponse("Hello, are you working?", []);
        
        return response()->json([
            'status' => 'SUCCESS',
            'response' => $response
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString())
        ], 500);
    }
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| REDIS TEST ROUTE (Development Only)
|--------------------------------------------------------------------------
*/
Route::get('/test-redis', function() {
    try {
        // Test 1: Cache
        Cache::put('test-key', 'Redis is working! ✅', 600);
        $cached = Cache::get('test-key');
        
        // Test 2: Direct Redis
        Redis::set('direct-test', 'Direct Redis works! 🚀');
        $direct = Redis::get('direct-test');
        
        // Test 3: Performance
        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            Cache::put("perf-test-$i", "value-$i", 60);
            Cache::get("perf-test-$i");
        }
        $time = round((microtime(true) - $start) * 1000, 2);
        
        return response()->json([
            'status' => '✅ SUCCESS',
            'driver' => config('cache.default'),
            'cache_test' => $cached,
            'redis_test' => $direct,
            'performance' => $time . 'ms for 100 operations',
            'message' => 'Redis is configured correctly!',
            'redis_info' => [
                'client' => config('database.redis.client'),
                'host' => config('database.redis.default.host'),
                'port' => config('database.redis.default.port'),
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ ERROR',
            'driver' => config('cache.default'),
            'error' => $e->getMessage(),
            'solution' => 'Make sure Redis/Memurai is running and CACHE_DRIVER is set in .env'
        ], 500);
    }
});



