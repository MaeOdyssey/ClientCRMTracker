<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'notes',
        'preferred_contact',
    ];
    
    public function sessions()
{
    return $this->hasMany(Session::class);
}

}
