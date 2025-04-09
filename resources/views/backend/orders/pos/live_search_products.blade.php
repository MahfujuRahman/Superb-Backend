@foreach($products as $product)
    @php
        $variants = DB::table('product_variants')
                        ->leftJoin('products', 'product_variants.product_id', 'products.id')
                        ->leftJoin('colors', 'product_variants.color_id', 'colors.id')
                        ->leftJoin('product_sizes', 'product_variants.size_id', 'product_sizes.id')
                        ->select('product_variants.*', 'products.name as product_name', 'products.image as product_image', 'colors.name as color_name', 'product_sizes.name as size_name')
                        ->where('product_variants.product_id', $product->id)
                        ->get();

        $totalStock = $product->stock;
        $productPrice = $product->price;
        $productDiscountPrice = $product->discount_price;

        if(count($variants) > 0){

            $totalStock = 0;
            $variantMinDiscountPriceArray = [];
            $variantMinPriceArray = [];

            foreach($variants as $variant){
                $totalStock = $totalStock + $variant->stock;
                $variantMinDiscountPriceArray[] = $variant->discounted_price;
                $variantMinPriceArray[] = $variant->price;
            }

            $productDiscountPrice = min($variantMinDiscountPriceArray);
            $productPrice = min($variantMinPriceArray);
        }
    @endphp

    <li class="live_search_item">
        <img loading="lazy" src="{{ url($product->image) }}" alt="">
        <h6 class="live_search_product_title">
            <span class="d-block live_search_product_name">{{$product->name}} ({{$product->code}})</span>

            @if(count($variants) == 0)
            <span class="d-block" style="font-weight: 400; color: gray">
            --No Variant--
            </span>
            @else
            <span class="d-block" style="font-weight: 400; color: darkgreen">
            Variant Product
            </span>
            @endif

            <span class="d-block" style="font-weight: 400">
                @if($productDiscountPrice > 0)
                <del>৳{{$productPrice}}</del>
                ৳{{$productDiscountPrice}}
                @else
                ৳{{$productPrice}}
                @endif

                @if($totalStock)
                (Stock: {{$totalStock}})
                @endif
            </span>

        </h6>
        @if($totalStock)
            @if(count($variants) == 0)
            <a href="javascript:void(0)" onclick="addToCart({{$product->id}},0,0)" class="add_to_cart_btn"><i class="fas fa-cart-arrow-down fa-fw"></i></a>
            @else
            <a href="javascript:void(0)" onclick="showVariant({{$product->id}})" class="add_to_cart_btn"><i class="fas fa-cart-arrow-down fa-fw"></i></a>
            @endif
        @else
        <a href="javascript:void(0)" class="add_to_cart_btn stock_out">Stock Out</a>
        @endif
    </li>
@endforeach
