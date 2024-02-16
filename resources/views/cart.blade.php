<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
$user = auth()->user();
$cart = Cart::where('user_id', optional($user)->id)->first();
$count = 0;
if($cart){
$count = CartItem::where('cart_id', $cart->id)->sum('quantity');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear - Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/websiteStyle.css">
    
</head>

<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="images\ML Menswear Logo.JPG" alt="ML Menswear Logo">
            </a>
        </div>
        <?php
        $user = auth()->user();
        ?>
        <div class="search-bar">
        @if ($user && $user->usertype === 'admin') 
        <form action="/product" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        @else
        <form action="/products" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        @endif
        <div class="user-icons">
            <i class="fas fa-heart"></i>
            <a href="{{ url('/cart') }}"><i class="fas fa-shopping-basket"> ({{$count}}) </i></a>
            @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/profile') }}"><i class="fas fa-user"></i></a>
                    @else
                        <a href="{{ route('login') }}"><i class="fas fa-user"></i></a>
                    @endauth
            @endif
    </div>
    </header>
    <nav>
        <a href="{{ route('tops') }}">Tops</a>
        <a href="{{ route('coats-and-jackets') }}">Coats & Jackets</a>
        <a href="{{ route('trousers') }}">Trousers</a>
        <a href="{{ route('shoes') }}">Shoes</a>
        <a href="{{ route('accessories') }}">Accessories</a>
        <a href="{{route('orders')}}">Orders</a>
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
        
    </nav>
    <div class="container">
        <div class="promo-banner">
            Free Express Shipping on First Orders | Free Same Day Click and Collect | Free Standard Delivery
    </div>
         
    <main>
    <h1>Cart</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
        <br>
        <div class= "productContainer">
        <?php
            $user = auth()->user();
            $cart = Cart::where('user_id', optional($user)->id)->first();
            $totalPrice = 0;
            $cartItems = [];
            if ($cart) {
                $totalPrice = CartItem::where('cart_id', $cart->id)->sum(DB::raw('price * quantity'));
                $cartItems = optional($cart)->cartItems()->with('product')->get();
            }
            ?>
            @if ($cartItems)
            @foreach($cartItems as $cartItem) 
      <div class="product">
      <img src="{{ $cartItem->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
      <h3>{{$cartItem->product->name}}</h3>
        <p>£{{$cartItem->price}}</p>
        <p>Quantity: {{$cartItem->quantity}}</p>
            <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a></p>
              
            @endforeach
            
            <td><td><td><td data-th="Total" class="text-center"><h4>Total Price:</h4> £{{$totalPrice}}</td>
        
        <br><br><a href="{{url('/checkout')}}"><button class="btn btn-success">Checkout</button></a>
        @else    
        Cart is empty!
        @endif
    </div>

    </main>
    <!-- Footer section -->
    <footer>
      <div class="business-details">
          <p>Email: info@mlmenswear.com</p>
          <p>Contact Number: +44 1234 567890</p>
          <p>Address: 123 Fashion Street, London, UK</p>
      </div>
      <p>&copy; 2024 ML Menswear. All rights reserved.</p>
  </footer>
  </body>
</html>