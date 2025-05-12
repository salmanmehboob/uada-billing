<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotCharges extends Model
{
    use HasFactory;

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault();
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class)->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(ChargeType::class,'charge_type_id')->withDefault();
    }


}
