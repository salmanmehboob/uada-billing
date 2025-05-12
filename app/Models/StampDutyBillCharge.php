<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyBillCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'stamp_duty_bill_id' ,
        'from_month',
        'from_year',
        'to_month',
        'to_year',
        'total_months',
        'no_of_transfer',
        'amount',
        'total',

    ];
    public function fromMonth(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'from_month')->withDefault();
    }

    public function toMonth()
    {
        return $this->belongsTo(Month::class,'to_month')->withDefault();
    }

}
