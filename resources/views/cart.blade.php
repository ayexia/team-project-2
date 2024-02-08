<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Cart</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <a href="{{route('home')}}">Back</a>
        <br>
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php
            $user = auth()->user();
            $cart = Cart::where('user_id', auth()->user()->id)->first();
            $totalPrice = CartItem::where('cart_id', $cart->id)->sum(DB::raw('price * quantity'));
            $cartItems = DB::table('cart_items')
            ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->select('cart_items.quantity', 'products.name', 'cart_items.price', 'products.image_url', 'cart_items.id')
            ->where('cart_items.cart_id', $cart->id)
            ->get();
            ?>
            @if ($cartItems->isNotEmpty())
            @foreach($cartItems as $cartItem) 
            <tr><td><div class="col-sm-9">
            <div class="row">
             <div class="col-sm-3 hidden-xs"><img src="{{ $cartItem->image_url }}" style="width: 200px; height: 200px;" class="card-img-top"/></div>
              <h4 class="nomargin">{{ $cartItem->name }}</h4>
                </div>
                </td>
                <td data-th="Price">£{{ $cartItem->price }}</td>
                <td data-th="Quantity">{{ $cartItem->quantity }}</td>
                <td class="actions">
                    <a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a>
                </td>
            </tr>
            @endforeach
            <td><td><td><td data-th="Total" class="text-center"><h4>Total Price:</h4> £{{$totalPrice}}</td>
        </table>
        @else    
        Records not found
        @endif
        <br><button class="btn btn-success" style="margin-left:25%">Confirm Order</button>
    </div>
</body>
</html>