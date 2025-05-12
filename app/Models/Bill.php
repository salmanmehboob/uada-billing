<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'allotee_id',
        'bank_id',
        'sector_id',
        'size_id',
        'year',
        'from_month',
        'to_month',
        'total_months',
        'issue_date',
        'due_date',
        'is_paid',
        'generated_by',
        'is_period',
        'bill_total',
        'arrears',
        'total',
        'sub_charges',
        'sub_total',
        'is_active',
        'is_time_period',
        'is_non_time_period',
        'is_violation',
        'is_non_user',
        'is_stamp_duty',
        'is_transfer',
        'is_possession',
    ];

    public function charges()
    {
        return $this->hasMany(BillCharge::class);
    }

    public static function generateBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')->where('is_time_period', true)
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix') . $currentYear . '-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        return getSettingValue('invoice_prefix') . $currentYear . '-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function generateNonTimePeriodBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')->where('is_non_time_period', true)
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix') . $currentYear . '-NP-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }
//        dd($nextBillNumber);

        return getSettingValue('invoice_prefix') . $currentYear . '-NP-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function generateTransferBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')->where('is_transfer', true)
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix') . $currentYear .   '-TR-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        return getSettingValue('invoice_prefix') . $currentYear . '-TR'. '-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
    }
    public static function generatePossessionBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')->where('is_possession', true)
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix') . $currentYear .   '-PS-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        return getSettingValue('invoice_prefix') . $currentYear . '-PS'. '-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
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
        return $this->belongsTo(Month::class, 'from_month')->withDefault();
    }

    public function toMonth()
    {
        return $this->belongsTo(Month::class, 'to_month')->withDefault();
    }

    public function billCharges()
    {
        return $this->hasMany(BillCharge::class);
    }

    public function transaction()
    {
        return $this->hasOne(BillTransaction::class);
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
