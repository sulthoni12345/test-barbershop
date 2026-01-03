<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'service_id',
        'qty',
        'total',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}    



