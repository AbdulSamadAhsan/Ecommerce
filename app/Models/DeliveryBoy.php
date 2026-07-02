<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model
{

    protected $guarded=[];
    public function user(){
         return $this->belongsTo(User::class);
    }
    public function assignments()
{
    return $this->hasMany(DeliveryAssignment::class, 'delivery_boy_id');
}

public function shipments()
{
    return $this->belongsToMany(
        Shipment::class,
        'delivery_assignments',
        'delivery_boy_id',
        'shipment_id'
    )->withPivot([
        'assigned_at',
        'picked_at',
        'delivered_at',
        'status',
        'remarks',
        'failed_reason',
    ])->withTimestamps();
}
}