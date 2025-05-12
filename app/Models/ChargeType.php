<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeType extends Model
{
    use HasFactory , SoftDeletes;

    public function charges()
    {
        return $this->hasMany(PlotCharges::class)->withDefault();
    }
}
