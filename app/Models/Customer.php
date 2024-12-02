<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone_number', 'email'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}