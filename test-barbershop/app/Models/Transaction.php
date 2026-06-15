<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

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
