<?php
use App\Models\Product;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
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
            @if ($cartItems)
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
            
            <br>
            <h3>Address</h3>
            <form action="{{ route('order') }}" method="POST">
    @csrf
    <input type="text" name="door_number" placeholder="Door number" maxlength="5" title="Please enter your door number" required><br><br>
    <input type="text" name="street" placeholder="Street" title="Please enter your street" required><br><br>
    <input type="text" name="city" placeholder="City" title="Please enter your city" required><br><br>
    <input type="text" name="postcode" placeholder="Postcode" maxlength="8" title="Please enter your postcode" required><br><br>
    
    <h3>Payment Details</h3><br>
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" pattern="[0-9]{16}" title="Please enter a 16-digit card number" required><br><br>
    
    <label for="expiry_date">Expiry Date:</label>
    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required><br><br>
    
    <label for="cvc">CVC:</label>
    <input type="text" id="cvc" name="cvc" placeholder="123" maxlength="3" pattern="[0-9]{3}" title="Please enter a 3-digit CVC" required><br><br>
    
    <label for="name_on_card">Name on Card:</label>
    <input type="text" id="name_on_card" name="name_on_card" placeholder="Name on Card" title="Please enter your name on your card" required><br><br>
    
    <button type="submit">Submit</button>
</form>
        <br><br><button class="btn btn-success" style="margin-left:25%">Place Order</button></a>
        </form>
            @else
        <?php  
        $cartSession = session('cart', []);
        ?>
        @dump(session('cart'))
        @if ($cartSession)
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
        <br>
        <h3>Name</h3>
        <form action="{{ route('order') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" title="Please enter your name" required><br><br>
        <h3>Email Address</h3>
        <input type="email" name="email" placeholder="Email Address" title="Please enter your email" required><br><br>

        <h3>Address</h3>
    <input type="text" name="door_number" placeholder="Door number" maxlength="5" title="Please enter your door number" required><br><br>
    <input type="text" name="street" placeholder="Street" title="Please enter your street" required><br><br>
    <input type="text" name="city" placeholder="City" title="Please enter your city" required><br><br>
    <input type="text" name="postcode" placeholder="Postcode" maxlength="8" title="Please enter your postcode" required><br><br>
    
    <h3>Payment Details</h3><br>
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" pattern="[0-9]{16}" title="Please enter a 16-digit card number" required><br><br>
    
    <label for="expiry_date">Expiry Date:</label>
    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required><br><br>
    
    <label for="cvc">CVC:</label>
    <input type="text" id="cvc" name="cvc" placeholder="123" maxlength="3" pattern="[0-9]{3}" title="Please enter a 3-digit CVC" required><br><br>
    
    <label for="name_on_card">Name on Card:</label>
    <input type="text" id="name_on_card" name="name_on_card" placeholder="Name on Card" title="Please enter your name on your card" required><br><br>
    
    <button type="submit">Submit</button>
    </form>
        @endif
        @endif
    </div>
    <script>
    document.getElementById('card_number').addEventListener('input', function(event) {
        this.value = this.value.replace(/\D/g, '');
    });

    document.getElementById('expiry_date').addEventListener('input', function(event) {
            var input = this.value;
            
            input = input.replace(/\D/g, '');

            if (input.length > 4) {
                input = input.substr(0, 4);
            }
            
            if (input.length > 2 && input.indexOf('/') === -1) {
                input = input.substr(0, 2) + '/' + input.substr(2);
            }
            this.value = input;
            var parts = input.split('/');
            if (parts.length === 2) {
                var month = parseInt(parts[0], 10);
                var year = parseInt(parts[1], 10);
                var currentDate = new Date();
                var currentYear = currentDate.getFullYear() % 100;
                if (month < 1 || month > 12 || year < currentYear || (year === currentYear && month < (currentDate.getMonth() + 1))) {
                    this.setCustomValidity('Please enter a valid expiry date (MM/YY).');
                } else {
                    this.setCustomValidity('');
                }
            } else {
                this.setCustomValidity('Please enter a valid expiry date (MM/YY).');
            }
        });

    document.getElementById('cvc').addEventListener('input', function(event) {
        this.value = this.value.replace(/\D/g, '');
    });
</script>
</body>
</html>