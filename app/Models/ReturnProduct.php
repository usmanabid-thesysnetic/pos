<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    protected $fillable = ['return_id', 'product_id', 'discount'];

    public function return()
    {
        return $this->belongsTo(Returns::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
