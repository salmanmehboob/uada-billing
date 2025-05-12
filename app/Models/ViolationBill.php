<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViolationBill extends Model
{
    use HasFactory;

    protected $table = 'violation_bills';
    protected $fillable = [
        'bill_number',
        'allotee_id',
        'bank_id',
        'sector_id' ,
        'size_id',
        'year',
        'from_month' ,
        'to_month',
        'total_months',
        'issue_date',
        'due_date',
        'is_paid' ,
        'generated_by' ,
        'is_period' ,
        'bill_total',
        'arrears',
        'total',
        'sub_charges' ,
        'sub_total',
        'is_active',


    ];

    public static function generateBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = Bill::orderBy('id', 'desc')->where('is_violation',true)
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix')  . $currentYear . '-V-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        return getSettingValue('invoice_prefix')  . $currentYear . '-V-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
    }

    public function allotee()
    {
        return $this->belongsTo(Allotee::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function fromMonth(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'from_month')->withDefault();
    }

    public function toMonth()
    {
        return $this->belongsTo(Month::class,'to_month')->withDefault();
    }

    public function billCharges()
    {
        return $this->hasMany(ViolationBillCharge::class);
    }

    public function transaction()
    {
        return $this->hasOne(ViolationBillTransaction::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');

    }


}
