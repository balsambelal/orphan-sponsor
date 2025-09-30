<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Sponsor extends Authenticatable
{
    protected $fillable = ['name','email','password', 'bank_account','address', 'phone'];
    protected $hidden = ['password'];

    public function orphans()
    {
        return $this->belongsToMany(Orphan::class, 'sponsorships');
    }
    public function sponsorships() { return $this->hasMany(Sponsorship::class); }


}
