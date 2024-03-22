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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/websiteStyle.css">
    <style>
        body{
            background-color:#f5eee7;
            font-family: Arial, Helvetica, sans-serif;
        }
        .productContainer {
            max-height: 50%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }
        .product{
            display: flex;
            justify-content: space-evenly;
        }
        .product img{
            margin-top: 25px;
            border: 1px gray;
            border-radius: 20px;
            height:200px;
            width: 200px;
            object-fit: cover!important;
        }
        .summary {
            width: 40%;            
        }
        .footer {
            background-color: gray; /* Footer background color */
            padding: 20px;
            text-align: center;
        }
        iframe[seamless] {
            border: none;
        }
        .list {
          position: relative;
        }
        .list h2 {
          color: #000;
          font-weight: 700;
          letter-spacing: 1px;
          margin-bottom: 10px;
        }
        .list ul {
          position: relative;
        }
        .list ul li {
          position: relative;
          left: 0;
          color: black;
          list-style: none;
          margin: 4px 0;
          border-left: 2px solid lightseagreen;
          transition: 0.5s;
          cursor: pointer;
          height: 25px;
          max-width: 70%;
          
        }
        .list ul li:hover {
          left: 10px;
        }
        .list ul li span {
          position: relative;
          padding: 2px;
          padding-left: 12px;
          display: inline-block;
          z-index: 1;
          transition: 0.5s;
        }
        .list ul li:hover span {
          color: white;
        }
        .list ul li:before {
          content: "";
          position: absolute;
          width: 100%;
          height: 100%;
          background: lightskyblue;
          transform: scaleX(0);
          transform-origin: left;
          transition: 0.5s;
        }
        .list ul li:hover:before {
          transform: scaleX(1);
        }
        .btn-primary{
          background-color: lightseagreen !important;
          border-color: lightseagreen !important;
        }
        .input{
            position: relative;
        }
        .input i{
           position: absolute;
            top: 10px;
            left: 11px;
            color: #989898;
        }
        .input input{
        text-indent: 25px;
        }
        /*header,footer,nav styles */
        body {
              font-family: Arial, sans-serif;
              margin: 0;
              padding: 0;
              background-color: #f0f0f0;
              color: #333; /* Main text color */
          }
          
          header {
              background-color: #000;
              color: #fff;
              padding: 20px;
              display: flex;
              justify-content: space-between;
              align-items: center;
          }
          
          nav {
              background-color: #333;
              color: #fff;
              padding: 10px;
              text-align: center;
              display: flex;
              justify-content: center; /* Center the nav items */
              align-items: center;
              flex: 1;
          }
          
          nav a {
              color: #fff;
              text-decoration: none;
              padding: 10px;
              margin-right: 20px;
          }
          
          .content {
              background-color: #fff;
              padding: 20px;
              margin-bottom: 20px;
              width: 100%;
          }
          
          footer {
              background-color: #000;
              color: #fff;
              text-align: center;
              padding: 20px;
              position: fixed;
              bottom: 0;
              width: 100%;
          }
          /* Teal color for headers */
          h1, h2 {
              color: #008080;
          }
          
          .teal {
              color: #008080;
          }
          
          /* Align search bar to the right */
          .search-bar {
              text-align: right;
              display: flex;
              justify-content: flex-end;
              align-items: center;
              flex: 1;
          }
          
          .search-bar input[type="text"] {
              margin-right: 10px;
          }
          
          .user-icons {
              display: flex;
              align-items: center;
          }
          
          .user-icons i {
              margin-right: 10px;
              color: #fff;
              cursor: pointer;
          }
          
          @media (max-width: 768px) {
              nav {
                  display: none;
              }
          }
          #hamburger
{
  display: block;
  position: relative;
  top: -40px;
  left: 50px;
  
  z-index: 1;
  
  -webkit-user-select: none;
  user-select: none;
}

#hamburger a
{
  text-decoration: none;
  color: #232323;
  
  transition: color 0.3s ease;
}

#hamburger a:hover
{
  color: tomato;
}


#hamburger input
{
  display: block;
  width: 40px;
  height: 32px;
  position: absolute;
  top: -7px;
  left: -5px;
  cursor: pointer;
  opacity: 0;
  z-index: 2;
  -webkit-touch-callout: none;
}
#hamburger span
{
  display: block;
  width: 20px;
  height: 2px;
  margin-bottom: 5px;
  position: relative;
  background: #cdcdcd;
  border-radius: 3px;
  z-index: 1;
  transform-origin: 4px 0px;
  transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
              background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
              opacity 0.55s ease;
}

#hamburger span:first-child
{
  transform-origin: 0% 0%;
}

#hamburger span:nth-last-child(2)
{
  transform-origin: 0% 100%;
}
#hamburger input:checked ~ span
{
  opacity: 1;
  transform: rotate(45deg) translate(1px, -1px);
  background: #232323;
}
#hamburger input:checked ~ span:nth-last-child(3)
{
  opacity: 0;
  transform: rotate(0deg) scale(0.1, 0.1);
}
#hamburger input:checked ~ span:nth-last-child(2)
{
  transform: rotate(-45deg) translate(3px, -2px);
}
#menu
{
  position: absolute;
  width: 300px;
  height: 100vh;
  margin: -45px 0 0 -50px;
  padding: 50px;
  padding-top: 125px;
  background: #ededed;
  list-style-type: none;
  -webkit-font-smoothing: antialiased;  
  transform-origin: 0% 0%;
  transform: translate(-100%, 0);
  transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0);
}
#menu li
{
  padding: 10px 0;
  font-size: 22px;
}
#hamburger input:checked ~ ul
{
  transform: none;
}
.menu-collapse {
  background-color: #ededed;
  cursor: pointer;
  outline: none;
  border: 0px!important;
}

.active, .menu-collapse:hover {
  background-color: #ededed;
}

.content {
  padding: 0 5px;
  display: none;
  overflow: hidden;
  background-color: #ededed;
}
    </style>
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
        <a href="{{ route('tops') }}">Tops</a>
        <a href="{{ route('coats-and-jackets') }}">Coats & Jackets</a>
        <a href="{{ route('trousers') }}">Trousers</a>        
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
        <div><img src="{{ $cartItem->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
        <div>
      <h3>{{$cartItem->product->name}}</h3>
        <p>£{{$cartItem->price}}</p>
        <form action="{{ route('update.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
            <p>Quantity: <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="0" max="{{$cartItem->product->quantity}}">
                <button class="btn btn-primary" type="submit">Update</button></p>
        </form>
            <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a></p></div>
              
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