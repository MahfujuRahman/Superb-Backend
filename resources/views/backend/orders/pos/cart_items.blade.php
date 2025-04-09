@if(session('cart') && count(session('cart')) > 0)
    @php $serial = 1; @endphp
    @foreach(session('cart') as $cartIndex => $details)
    <tr>
        <th class="text-center">
            {{$serial++}}
            <input type="hidden" name="product_id[]" value="{{$details['product_id']}}">
            <input type="hidden" name="color_id[]" value="{{$details['color_id']}}">
            <input type="hidden" name="size_id[]" value="{{$details['size_id']}}">
            <input type="hidden" name="purchase_product_warehouse_id[]" value="{{$details['purchase_product_warehouse_id']}}">
            <input type="hidden" name="purchase_product_warehouse_room_id[]" value="{{$details['purchase_product_warehouse_room_id']}}">
            <input type="hidden" name="purchase_product_warehouse_room_cartoon_id[]" value="{{$details['purchase_product_warehouse_room_cartoon_id']}}">
            <input type="hidden" name="price[]" value="{{$details['price']}}">
        </th>
        <td class="text-center">
            <img src="{{url($details['image'])}}" style="width: 30px; height: 30px">
        </td>
        <td class="text-left">
            {{$details['name']}} ({{$details['code']}})
            @if($details['color_id']) <b>Color:</b> {{$details['color_name']}} @endif
            @if($details['size_id']) <b>Size:</b> {{$details['size_name']}} @endif
        </td>
        <td class="text-center">৳{{$details['price']}}</td>
        <td class="text-center">
            <input type="text" class="text-center" style="width: 50px;" min="1" onkeyup="updateCartQty(this.value, '{{$cartIndex}}')" value="{{$details['quantity']}}" name="quantity[]" placeholder="1" required/>
        </td>
        <td class="text-center">৳{{$details['price']*$details['quantity']}}</td>
        <td class="text-center">
            <button type="button" onclick="removeCartItem('{{$cartIndex}}')" class="btn btn-danger btn-sm m-0">
                <i class="fa fa-times"></i>
            </button>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center">No item is Added</td>
    </tr>
@endif
