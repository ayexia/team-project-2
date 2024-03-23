<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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
    <title>ML Menswear - Order History</title>
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
    <h1>Order History</h1>
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
        if ($user) {
            $orders = Order::where('user_id', $user->id)->get();
        } else {
            $guestIdentifier = session()->get('guest_identifier');
            $orders = Order::where('guest_identifier', $guestIdentifier)->get();
        }
        ?>
         @if($orders->isNotEmpty())
         @foreach ($orders as $order) 
             <?php $orderItems = OrderItem::where('order_id', $order->id)->get(); ?>

             <h2>Order Number: {{ $order->id }}</h2>
            
        @foreach ($orderItems as $item)
        <div class="product">
        <img src="{{ $item->product->image_url }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
        <h3>{{$item->product->name}}</h3>
        <p>£{{$item->product->price}}</p>
        <p>Quantity: {{ $item->quantity }}</p>
                @endforeach
                <tr>
                    <td colspan="5"><h4>Address:</h4> {{ $order->address }}</td>
                </tr>
                <tr>
                    <td colspan="5"><h4>Status:</h4> {{ $order->status }}</td>
                </tr>
                <tr>
                    <td colspan="5"><h4>Total Price:</h4> £{{ $order->total_price }}</td>
                </tr>
                <tr>
                    <td colspan="5"><h4>Order Date:</h4> {{ $order->order_date }}</td>
                </tr>
                <br><br>
                @if ($order->status === 'Pending' || $order->status === 'Processing')
            <form method="POST" action="{{ route('order.cancel', ['order' => $order->id]) }}">
                @csrf
                <button type="submit">Cancel Order</button>
            </form>
        @endif
        @endforeach
        @else
        No orders placed
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