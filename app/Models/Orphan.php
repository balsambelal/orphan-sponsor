<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Orphan extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول التي يمكن تعبئتها
     */
    protected $fillable = [
        'name',
        'birthdate',
        'gender',
        'address',
        'identity_number',
        'email',
        'password',
        'notes',
        'child_image',
        'birth_certificate',
        'death_certificate',
        'guardian_id',
        'custody_document',
        'bank_account',
        'country',
        'city',
        'bank_name',
        'education_status',
        'is_sponsored',
        'is_active', 
        'is_verified',  


    ];

    /**
     * الحقول التي يجب إخفاؤها عند الإخراج
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * الحقول المضافة تلقائيًا عند الإخراج
     */
    protected $appends = [
        'is_sponsored',
    ];
// app/Models/Orphan.php

public function sponsors()
{
    return $this->belongsToMany(Sponsor::class, 'sponsorships');
}

    /**
     * العلاقة مع جدول الكفالات
     */
  public function sponsorships()
{
    return $this->hasMany(Sponsorship::class);
}


    /**
     * خاصية محسوبة لمعرفة إذا كان اليتيم مكفول
     */
    public function getIsSponsoredAttribute()
    {
        return $this->sponsorships()->exists();
    }

    /**
     * تعديل كلمة المرور تلقائيًا إذا تم إدخالها
     */
public function setPasswordAttribute($value)
{
    if (!empty($value)) {
        $this->attributes['password'] = bcrypt($value);
    }
}




}


