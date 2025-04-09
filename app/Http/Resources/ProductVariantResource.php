<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'image' => $this->image,
            'color_id' => $this->color_id,
            'color_name' => $this->color_name,
            'size_id' => $this->size_id,
            'size_name' => $this->size_name,
            'color_code' => $this->color_code,
            'region_id' => $this->region_id,
            'region_name' => $this->region_name,
            'sim_id' => $this->sim_id,
            'sim_type' => $this->sim_type,
            'storage_type_id' => $this->storage_type_id,
            'storage_type' => $this->storage_type,
            'stock' => $this->stock,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'device_condition_id' => $this->device_condition_id,
            'warrenty_id' => $this->warrenty_id,
            'product_warrenty' => $this->product_warrenty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
