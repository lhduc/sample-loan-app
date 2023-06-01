<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *      schema="UserSchema",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="User ID",
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          nullable=false,
 *          example="Duc Le"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          nullable=false,
 *          example="lehongduc87@gmail.com"
 *      ),
 *      @OA\Property(
 *          property="type",
 *          type="integer",
 *          nullable=false,
 *          enum={
 *             "1 - admin",
 *             "2 - user",
 *          },
 *          example="2"
 *      )
 * )
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $type
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
}
