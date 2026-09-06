<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model {
    protected $fillable = ['name', 'email', 'role', 'company_id', 'token', 'accepted'];
    public function company() {
        return $this->belongsTo(Company::class); 
    }
}
