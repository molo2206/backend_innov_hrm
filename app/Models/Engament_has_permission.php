<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engament_has_permission extends Model
{
    use HasFactory, HasUuids;
    protected $table = "engagement_has_permissions";
    protected $fillable = ['permission_id', 'engagement'];
}
