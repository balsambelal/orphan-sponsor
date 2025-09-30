<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = [
    'orphan_id',
    'sponsor_id',
    'amount',
    'account_no',
    'start_date',
    'end_date',
    'status',
];


public function orphan()
    {
        return $this->belongsTo(Orphan::class);
    }

public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

}


