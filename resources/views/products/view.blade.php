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
    <meta name="authors" content="Ayesha, Nagina">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$product->name}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/websiteStyle.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="/images/ML Menswear Logo.JPG" alt="ML Menswear Logo">
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
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div class="product-container">
      <div class="product-image">
      <img src="{{$product->image_url}}" alt="Product Image" style="width: 400px; height: 400px;">                  
      </div>
      <div class="product-description">
        <h2 class="product-title">{{$product->name}}</h2>
        <p class="product-price">£{{$product->price}}</p>
        <div class="product-details">
        <!-- renders and displays new lines from table data -->
            <p>{!! nl2br(e($product->description)) !!}</p><br>
            <p>Colour: {{$product->colour}}<br>
               Brand: {{$product->brand}}<br>
               Size: {{$product->size}}<br>
               In: {{ $product->category->name }}</p><br>
        </div>
        <form action="{{ route('add.to.cart', $product->id) }}" class="btn btn-outline-danger"> 
        @csrf
        <button class="btn btn-primary" type="submit" value="Add to Cart">
            <i class="fas fa-shopping-basket basket-icon"></i>
            Add to Basket
        </button>
</form>
      </div>
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