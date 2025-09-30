<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 


class Orphan extends Authenticatable
{
    use HasFactory;

   protected $fillable = [
    'name', 'birthdate', 'gender', 'address', 'identity_number', 'email', 'password',
 'notes','is_sponsored', 'child_image', 'birth_certificate',
    'death_certificate', 'guardian_id', 'custody_document',
    'bank_account' 
];
 protected $hidden = [
        'password',
        'remember_token',
    ];

public function sponsorships()
{
    return $this->hasMany(Sponsorship::class);
}

// للتحقق إذا كان اليتيم مكفول
public function isSponsored()
{
    return $this->sponsorships()->count() > 0;
}


}

