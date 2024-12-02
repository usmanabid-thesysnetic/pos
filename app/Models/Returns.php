<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $fillable = ['user_id', 'sale_id', 'ref_no', 'total', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function returnProducts()
    {
        return $this->hasMany(ReturnProduct::class);
    }
}
