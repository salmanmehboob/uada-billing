<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allotee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plot_no',
        'name',
        'email',
        'phone_no',
        'account_no',
        'contact_person_name',
        'address',
        'sector_id',
        'size_id',
        'type_id',
        'is_active',
        'arrears',
        'guardian_name',


    ];

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault();
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class)->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withDefault();
    }

}

