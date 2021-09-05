<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 *
 * @OA\Schema(
 *    required={"email","password"},
 *    @OA\Xml(name="User"),
 *    @OA\Property(property="id", type="integer", readOnly="true", example=1),
 *    @OA\Property(property="email", type="string", format="email", description="User unique email address", example="user@gmail.com")
 * )
 *
 * Class User
 *
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [ 'email', 'password' ];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $casts = [ 'email_verified_at' => 'datetime' ];


    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
