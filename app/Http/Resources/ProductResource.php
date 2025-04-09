<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Flag;
use App\Models\ProductImage;
use App\Models\ProductQuestionAnswer;
use App\Models\ProductReview;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        $variants = DB::table('product_variants')
                        ->leftJoin('product_sizes', 'product_variants.size_id', 'product_sizes.id')
                        ->leftJoin('colors', 'product_variants.color_id', 'colors.id')
                        ->leftJoin('country', 'product_variants.region_id', 'country.id')
                        ->leftJoin('sims', 'product_variants.sim_id', 'sims.id')
                        ->leftJoin('storage_types', 'product_variants.storage_type_id', 'storage_types.id')
                        ->leftJoin('device_conditions', 'product_variants.device_condition_id', 'device_conditions.id')
                        ->leftJoin('product_warrenties', 'product_variants.warrenty_id', 'product_warrenties.id')
                        ->select('product_variants.*', 'colors.name as color_name', 'colors.code as color_code', 'product_sizes.name as size_name', 'country.name as region_name', 'sims.name as sim_type', DB::Raw("CONCAT(storage_types.ram, '/', storage_types.rom) AS storage_type"), 'device_conditions.name as device_condition', 'product_warrenties.name as product_warrenty')
                        ->where('product_id', $this->id)
                        ->get();

        $categoryInfo = Category::where('id', $this->category_id)->first();
        $subcategoryInfo = Subcategory::where('id', $this->subcategory_id)->first();
        $childcategoryInfo = ChildCategory::where('id', $this->childcategory_id)->first();
        $flagInfo = Flag::where('id', $this->flag_id)->first();
        
        $totalStockAllVariants = 0;
        if($variants && count($variants) > 0){
            foreach ($variants as $variant) {
                $totalStockAllVariants = $totalStockAllVariants + (int) $variant->stock;
            }
        }
        $totalStockAllVariants = $totalStockAllVariants + $this->stock;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'category_slug' => $categoryInfo ? $categoryInfo->slug : '',
            'subcategory_id' => $this->subcategory_id,
            'subcategory_name' => $this->subcategory_name,
            'subcategory_slug' => $subcategoryInfo ? $subcategoryInfo->slug : '',
            'childcategory_id' => $this->childcategory_id,
            'childcategory_name' => $this->childcategory_name,
            'childcategory_slug' => $childcategoryInfo ? $childcategoryInfo->slug : '',
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
            'model_id' => $this->model_id,
            'model_name' => $this->model_name,
            'image' => $this->image,
            'multiple_images' => ProductImageResource::collection(ProductImage::where('product_id', $this->id)->get()),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'specification' => $this->specification,
            'warrenty_policy' => $this->warrenty_policy,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'stock' => $totalStockAllVariants,
            'unit_id' => $this->unit_id,
            'unit_name' => $this->unit_name,
            'tags' => $this->tags,
            'video_url' => $this->video_url,
            'warrenty_id' => $this->warrenty_id,
            'product_warrenty' => $this->product_warrenty,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'flag_id' => $this->flag_id,
            'flag_name' => $this->flag_name,
            'flag_slug' => $flagInfo ? $flagInfo->slug : null,
            'flag_icon' => $flagInfo ? $flagInfo->icon : null,
            'average_rating' => number_format(ProductReview::where('product_id', $this->id)->where('status', 1)->avg('rating'), 1),
            'review_count' => ProductReview::where('product_id', $this->id)->where('status', 1)->count(),
            'reviews' => ProductReviewResource::collection(ProductReview::where('product_id', $this->id)->where('status', 1)->get()),
            'has_variant' => $this->has_variant,
            'variants' => ProductVariantResource::collection($variants),
            'questions' => ProductQuestionAnswerResource::collection(ProductQuestionAnswer::where('product_id', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}