<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = "users";
    protected $fillable =
    [
        'first_name',
        'last_name',
        'prename',
        'gender',
        'phone',
        'email',
        'qrcode',
        'password',
        'phone',
        'fingerprint',
        'image',
        'type'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function engagement()
    {
        return $this->belongsToMany(Company::class, 'engagements', 'iduser', 'idcompany');
    }

    public function commit()
    {
        return $this->belongsTo(Commitments::class, 'id', 'iduser');
    }

    public function checkPermission($name, $action)
    {
        $exis = $this->commit->permissions()->where('name', $name)->where('status', 1)->where('deleted', 0)->first();
        if ($exis) {
            if ($exis->access->$action) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
