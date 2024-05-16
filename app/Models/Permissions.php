<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory, HasUuids;
    protected $table= 'permissions';
    protected $fillable = ['name', 'status', 'deleted'];
}
