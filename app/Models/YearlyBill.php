<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YearlyBill extends Model
{
    use HasFactory;

    protected $table = 'yearly_bills';
    protected $fillable = [
        'bill_number',
        'allotee_id',
        'bank_id',
        'sector_id' ,
        'size_id',
        'issue_date',
        'due_date',
        'is_paid' ,
        'generated_by' ,
        'is_period' ,
        'total',
        'sub_charges' ,
        'sub_total',
        'is_active',


    ];

    public static function  generateBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')
            ->first();
         if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen(getSettingValue('invoice_prefix')  . $currentYear . '-STD-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        $newBillGenerateNumber = getSettingValue('invoice_prefix')  . $currentYear . '-STD'.   '-'. str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);

        return $newBillGenerateNumber;

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



    public function billCharges()
    {
        return $this->hasMany(YearlyBillCharge::class);
    }

    public function transaction()
    {
        return $this->hasOne(YearlyBillTransaction::class);
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
