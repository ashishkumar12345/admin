<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'company_id', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function shortUrls() {
        return $this->hasMany(ShortUrl::class);
    }

    public function isSuperAdmin(): bool {
        return $this->role === 'SuperAdmin'; 
    }
    public function isAdmin(): bool { 
        return $this->role === 'Admin'; 
    }
    public function isMember(): bool {
        return $this->role === 'Member'; 
    }
}