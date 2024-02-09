<?php
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
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
    <h1>Checkout</h1>
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
            $cart = Cart::where('user_id', optional($user)->id)->first();
            $totalPrice = 0;
            if ($cart) {
                $totalPrice = CartItem::where('cart_id', $cart->id)->sum(DB::raw('price * quantity'));
            }
            $cartItems = optional($cart)->cartItems()->with('product')->get();
            ?>
            @if ($cartItems->isNotEmpty())
            @foreach($cartItems as $cartItem) 
            <tr><td><div class="col-sm-9">
            <div class="row">
             <div class="col-sm-3 hidden-xs"><img src="{{ $cartItem->product->image_url }}" style="width: 200px; height: 200px;" class="card-img-top"/></div>
              <h4 class="nomargin">{{ $cartItem->product->name }}</h4>
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
        </table><br>
        <h3>Address</h3>
        <form action="{{ route('order') }}" method="POST">
        @csrf
        <input type="text" name="address" placeholder="Street"><br><br>
        <input type="text" name="address" placeholder="City"><br><br>
        <input type="text" name="address" placeholder="Postcode"><br><br>
        
        <h3>Payment Details</h3><br>
        <input type="text" placeholder="Card Number"><br><br>
        <input type="text" placeholder="Expiry Date">
        <input type="text" placeholder="CVC"><br><br>
        <input type="text" placeholder="Name on Card">
        <br><br><button class="btn btn-success" style="margin-left:25%">Place Order</button></a>
        </form>
        @endif
    </div>
</body>
</html>