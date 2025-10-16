<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Sponsor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bank_account',
        'address',
        'phone',
        'country',
        'city',
        'bank_name',
        'is_active',
        'is_verified',
    ];

    protected $hidden = ['password'];

    /**
     *  Mutator لتشفير كلمة المرور تلقائيًا عند الحفظ أو التحديث
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // لا تشفّر مرتين إذا كانت مشفّرة مسبقًا
            $this->attributes['password'] = 
                Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    /**
     * علاقة الكفيل بالأيتام عبر جدول sponsorships
     */
    public function orphans()
    {
        return $this->belongsToMany(Orphan::class, 'sponsorships');
    }

    /**
     * علاقة الكفيل بجداول الكفالات
     */
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class);
    }
}


