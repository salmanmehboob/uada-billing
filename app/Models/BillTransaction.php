<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'bill_id',
        'total' ,
        'is_paid',
        'paid_amount' ,
        'due_amount',
        'payment_date',

    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
