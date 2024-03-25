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
    <link rel="stylesheet" href="/css/websiteStyle.css">
    <style>


/* Product listing styles */
.container {
    width: 100%;
    margin: 0 ;
    padding: 0px;
    text-align: center;
}

.content {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    position: relative;
}

        
        main {
            margin-bottom: 50px;
        }

        .wrapper{
            display: flex!important;
            width: 100%!important;
        }

        .checkoutContainer{
            padding:20px;
            margin-left: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .productContainer {
            padding: 20px;
            padding-right: 50px;
            width: 70%!important;
            display: grid!important;
            grid-template-columns: repeat(1s, 1fr);
            gap: 20px;
            border-right: 1px solid gray;
        }
        
        .product {
            width: 100%!important;
            display: flex;
            justify-content: center;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .product img {
            width: 100%;
            height: auto;
            flex: 0 0 50%;
            margin-bottom: 10px;
        }

        .product-content{
            margin-left: 100px!important;
        }
        
        .product h3 {
            text-align: right;
            margin-bottom: 10px;
        }
        
        .product p {
            text-align: right;
            margin-bottom: 10px;
        }
        
        .product form {
            display: flex;
            align-items: center;
            justify-content: centre;
        }
        
        .product form input[type="number"] {
            width: 60px;
            margin-right: 10px;
        }
        
        .product form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        .product form button:hover {
            background-color: #0056b3;
        }
        
        .btn-success {
            background-color: #008080;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-success:hover {
            background-color: #008090;
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
        <form action="/products" method="GET">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form></div>
        <div class="user-icons">
        @if (Auth::check())
            <a href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-heart"></i></a>
        @endif
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
    @foreach($categories as $cat)
    @if ($loop->iteration <= 5)
        <a href="{{ route('view.category', ['category' => $cat->name]) }}">{{ $cat->name }}</a>
    @endif
    @endforeach
    @endif
        <a href="{{route('orders')}}">Orders</a>
        <a href="{{ route('about-us') }}">About Us</a>
        <a href="{{ route('contact-us') }}">Contact Us</a>
    
    </nav>
    <div>
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
        <div class="wrapper">
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
           <div class= "productContainer">
            @foreach($cartItems as $cartItem) 
        <div class="product">
        <div><img src="{{ $cartItem->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
        <div class="product-content">
      <h3 style="text-align:right;">{{$cartItem->product->name}}</h3>
        <p style="text-align:right;">£{{$cartItem->price}}</p>
        <form action="{{ route('update.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
            <p>Quantity: <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="0" max="{{$cartItem->product->quantity}}">
                <button class="btn btn-primary" type="submit">Update</button></p>
        </form>
            <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a></p></div>
          </div>
            @endforeach
          </div>
          <div class="checkoutContainer">
            <h4>Total Price:</h4> £{{$totalPrice}}
        
        <br><br><a href="{{url('/checkout')}}"><button class="btn btn-success">Checkout</button></a>
          </div>
        <?php  
        $cartSession = session('cart', []);
        ?>
        
        @elseif (!$user && $cartSession)
        <div class= "productContainer">
            @foreach($cartSession as $item)
                <div class="product">
                  <div><img src="{{ $item['image_url'] }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
                  <div class="product-content">
                    <h3 style="text-align:right;">{{ $item['name'] }}</h3>
                    <p style="text-align:right;">£{{ $item['price'] }}</p>
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
                  </div>
                  </div>
                    @endforeach
          </div>
          <div class="checkoutContainer">
                    <h4>Total Price:</h4> £{{$totalPrice}}
                    <br><br><a href="{{url('/checkout')}}"><button class="btn btn-success">Checkout</button></a>
          </div>
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