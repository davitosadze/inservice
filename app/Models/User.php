<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\Hash;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia, LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'inter_password',
        'manager_type'
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
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('credential')->singleFile();
    }


    public function dates()
    {
        return $this->hasMany(UserInvoice::class, "user_id");
    }

    public function reports()
    {
        return $this->hasMany(UserInvoice::class, "user_id");
    }


    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, "user_id");
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, "user_id");
    }

    public function signature()
    {
        if ($this->getMedia('*')->first() === null) {
            return;
        }
        return $this->getMedia('*')->first()?->original_url;
    }
}
