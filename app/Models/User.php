<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SebastianBergmann\Comparator\TypeComparator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'nomcomptentic',
        'email',
        'poste',
        'password',
        'tdepartment_id',
        'role_id',
    ];


    public function role()
    {
        return $this->belongsTo(Trole::class, 'role_id');
    }

    public function departement()
    {
        return $this->belongsTo(TDepartement::class, 'tdepartment_id');
    }


    public function client()  {
        return $this->belongsTo(TClient::class, 'client_id');
    }

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
    ];
}
