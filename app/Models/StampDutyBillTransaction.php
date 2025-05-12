<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyBillTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'stamp_duty_bill_id',
        'total' ,
        'is_paid',
        'paid_amount' ,
        'due_amount',
        'payment_date',

    ];

}
