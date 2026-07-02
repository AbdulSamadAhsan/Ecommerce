<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'phone',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }
     public function sales()
{
    return $this->hasMany(Sale::class);
}
    public function wallet(){
        return $this->hasOne(Wallet::class);
    }
       public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            Sale::class,
            'customer_id', // Foreign key on sales table
            'sale_id',     // Foreign key on orders table
            'id',          // Local key on customers
            'id'           // Local key on sales
        );
    }

    public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}
}