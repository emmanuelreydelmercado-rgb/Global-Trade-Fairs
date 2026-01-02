<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'package_type',
        'package_name',
        'amount',
        'breakdown',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status',
        'pdf_path',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'amount' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    // Relationship with User (optional)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
