<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViolationBillCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'violation_bill_id' ,
        'plot_charge_id',
        'amount',
        'total_violation',
        'total',
        'issue_date',
        'due_date',

    ];

    public function PlotCharges()
    {
        return $this->belongsTo(PlotCharges::class,'plot_charge_id');

    }

}
