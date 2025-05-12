<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YearlyBillTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'yearly_bill_id',
        'total' ,
        'is_paid',
        'paid_amount' ,
        'due_amount',
        'payment_date',

    ];

}
