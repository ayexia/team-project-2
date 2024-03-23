<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
$user = auth()->user();
if ($user) {
    $cart = Cart::where('user_id', optional($user)->id)->first();
    $count = 0;
    if($cart){
    $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        }
    } else {
        $cartSession = session('cart', []);
        $count = 0;
        $totalPrice = 0;
    
        foreach ($cartSession as $item) {
            $count += $item['quantity'];
        }
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
<style>
.product {
    display: flex;
    flex-direction: column; /* Stack children vertically */
    align-items: center; /* Center-align the items */
    margin-bottom: 20px; /* Space between items */
}

.product img {
    margin-bottom: 10px; /* Space between image and text */
}

/* Container adjustments for cart items and pricing */
.productContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

/* Individual cart item style adjustments */
.productContainer .product {
    width: calc(50% - 20px); /* Adjust based on layout preference */
    margin: 10px;
    text-align: center;
}

/* Styles for the subtotal and total price section */
.total-price {
    flex-basis: 100%; /* Ensure it spans the full width */
    text-align: right; /* Align the text to the right */
    margin-top: 20px; /* Space it out from the products */
}

/* Ensuring responsive behavior */
@media (max-width: 768px) {
    .productContainer {
        flex-direction: column;
    }

    .productContainer .product {
        width: 100%; /* Full width for smaller screens */
    }

    .total-price {
        text-align: center; /* Center-align for readability on small screens */
    }
}

/* Additional styling for form elements inside products for consistency */
.product form input[type="number"],
.product form button {
    padding: 5px 10px;
    margin: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.product form button {
    background-color: #008080;
    color: white;
    cursor: pointer;
}

.product form button:hover {
    background-color: #006666; /* Darker on hover */
}
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    color: #333;
    margin: 0;
    padding: 0;
}

a {
    color: #008080;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Header */
header {
    background-color: #000;
    color: #fff;
    display: flex;
    justify-content: space-between;
    padding: 20px;
    align-items: center;
}

.logo img {
    width: 120px;
    height: auto;
}

/* Search and User Icons */
.search-bar form {
    display: flex;
    align-items: center;
}

.search-bar input[type="text"] {
    padding: 10px;
    margin-right: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.user-icons i {
    margin: 0 10px;
    cursor: pointer;
}

/* Navigation */
nav {
    background-color: #333;
    display: flex;
    justify-content: center;
    padding: 10px 0;
}

nav a {
    color: #fff;
    margin: 0 15px;
    padding: 10px 15px;
}

/* Promo Banner */
.promo-banner {
    background-color: #008080;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

/* Main Content and Cart Items */
.container {
    padding: 20px;
    background-color: #fff;
}

.productContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.product {
    border: 1px solid #ddd;
    margin: 10px;
    padding: 10px;
    width: calc(33.333% - 20px);
    box-sizing: border-box;
    background-color: #f9f9f9;
}

.product img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
}

.product h3 {
    margin: 10px 0;
    color: #333;
}

/* Update and Delete Buttons */
button {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 5px;
}

.btn-outline-danger {
    background-color: #fff;
    color: #d9534f;
    border: 1px solid #d9534f;
}

.btn-outline-danger:hover {
    background-color: #d9534f;
    color: #fff;
}

.btn-success {
    background-color: #5cb85c;
    color: #fff;
}

.btn-success:hover {
    background-color: #4cae4c;
}

/* Footer */
footer {
    background-color: #000;
    color: #fff;
    text-align: center;
    padding: 20px;
    margin-top: 20px;
}

.business-details p {
    margin: 5px 0;
}</style>
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
        <a href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
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
    @if (!empty($categories))
        @foreach($categories as $category)
        @if ($loop->iteration <= 5)
            <a href="{{ route('view.category', ['category' => $category->name]) }}">{{ $category->name }}</a>
        @endif
        @endforeach
        @endif
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
           @php
            session()->forget('success');
            session()->save();
         @endphp
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
           @if ($user && $cartItems)
            @foreach($cartItems as $cartItem) 
      <div class="product">
      <img src="{{ $cartItem->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
      <h3>{{$cartItem->product->name}}</h3>
        <p>£{{$cartItem->price}}</p>
        <form action="{{ route('update.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
            <p>Quantity: <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="0" max="{{$cartItem->product->quantity}}">
                <button type="submit">Update</button></p>
        </form>
            <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a></p>
              
            @endforeach
            
            <td><td><td><td data-th="Total" class="text-center"><h4>Total Price:</h4> £{{$totalPrice}}</td>
        
        <br><br><a href="{{url('/checkout')}}"><button class="btn btn-success">Checkout</button></a>
        <?php  
        $cartSession = session('cart', []);
        ?>
        @dump(session('cart'))
        @elseif (!$user && $cartSession)
            @foreach($cartSession as $item)
                <div class="product">
                    <img src="{{ $item['image_url'] }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
                    <h3>{{ $item['name'] }}</h3>
                    <p>£{{ $item['price'] }}</p>
                    <form action="{{ route('update.cart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cart_item_id" value="{{ $item['id'] }}">
                    <p>Quantity: <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $item['max_quantity'] }}">
                    <button type="submit">Update</button></p>
                    </form>
                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $totalPrice += $subtotal;
                    ?>
                    <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', ['item' => $item['id']])}}">Delete</a></p>
                    @endforeach
                    <td><td><td><td data-th="Total" class="text-center"><h4>Total Price:</h4> £{{$totalPrice}}</td>
                    <br><br><a href="{{url('/checkout')}}"><button class="btn btn-success">Checkout</button></a>
        @else
        Cart is empty!
        @endif
    </div>
    <?php dd(session('guest_identifier')); ?><?php dd(session('guest_identifier')); ?>
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