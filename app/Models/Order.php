<?php

namespace App\Models;

use App\Http\Controllers\Inventory\Models\ProductWarehouse;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoom;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoomCartoon;
use App\Http\Controllers\Outlet\Models\CustomerSourceType;
use App\Http\Controllers\Outlet\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingInfo;

class Order extends Model
{
    use HasFactory;


    public function warehouse() {
        return $this->belongsTo(ProductWarehouse::class, 'warehouse_id');
    }

    public function room() {
        return $this->belongsTo(ProductWarehouseRoom::class, 'room_id');
    }

    public function cartoon() {
        return $this->belongsTo(ProductWarehouseRoomCartoon::class, 'cartoon_id');
    }

    public function customerSourceType() {
        return $this->belongsTo(CustomerSourceType::class, 'customer_src_type_id');
    }

    public function outlet() {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }


    public function shippingInfo() {
        return $this->hasOne(ShippingInfo::class, 'order_id');
    }

    // public function customerSourceType() {
    //     return $this->belongsTo(CustomerSourceType::class, 'customer_src_type_id');
    // }


}
