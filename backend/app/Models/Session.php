<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'date',
        'fee',
    ];

    // Optional: relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
