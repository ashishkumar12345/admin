<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model {
    protected $fillable = ['user_id', 'company_id', 'long_url', 'short_url', 'clicks'];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function company() { 
        return $this->belongsTo(Company::class); 
    }
}