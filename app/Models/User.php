<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     title="User",
 *     description="Модель пользователя",
 *     @OA\Xml(name="User")
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @OA\Property(
     *     title="ID",
     *     description="Идентификатор пользователя",
     *     format="int64",
     *     example=1
     * )
     *
     * @var int
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="Имя пользователя",
     *     example="Иван Иванов"
     * )
     *
     * @var string
     */
    public string $name;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Адрес электронной почты",
     *      example="test@example.com"
     * )
     *
     * @var string
     */
    public string $email;

    /**
     * @OA\Property(
     *     title="Email Verified at",
     *     description="Дата регистрации",
     *     format="datetime",
     *     type="string",
     *     nullable=true,
     *     example="2020-01-27 17:50:45"
     * )
     *
     * @var DateTime|null
     */
    private ?DateTime $email_verified_at;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Дата регистрации",
     *     format="datetime",
     *     type="string",
     *     example="2020-01-27 17:50:45"
     * )
     *
     * @var DateTime
     */
    private DateTime $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Дата обновления",
     *     format="datetime",
     *     type="string",
     *     example="2020-01-27 17:50:45"
     * )
     *
     * @var DateTime
     */
    private DateTime $updated_at;

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

    /**
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
