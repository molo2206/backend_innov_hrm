<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commitments extends Model
{
    use HasFactory, HasUuids;
    protected $table = "engagements";
    protected $fillable =
    [
        'iduser',
        'idcompany',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'engagement_has_permissions', 'engagement', 'permission_id')->withPivot(['create','read','update','delete'])->as('access')->where('deleted', 0)->where('status', 1);
    }
}
