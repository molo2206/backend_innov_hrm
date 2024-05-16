<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'company';
    protected $fillable=[
        'name',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'pays',
        'type',
        'website',
        'tva',
        'num_siert1',
        'num_siert2',
        'rccm',
        'idnat'
    ];
}
