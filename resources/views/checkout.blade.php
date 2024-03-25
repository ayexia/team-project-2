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
    <link rel="stylesheet" href="/css/websiteStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js" rel="javascript"/>
    <style>
        body {
            background-color: #f0f0f0!important;
        }
        .place-order{
            background-color: #008080;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .card{
         border: none;
        }
        .card-header {
             padding: .5rem 1rem;
             margin-bottom: 0;
             background-color: rgba(0,0,0,.03);
             border-bottom: none;
         }
         .form-control{
           height: 50px;
            border: 2px solid #eee;
            border-radius: 6px;
            font-size: 14px;
         }
         .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #039be5;
            outline: 0;
            box-shadow: none;
        }
        .input{
            position: relative;
        }
        .input i{
           position: absolute;
            top: 20px;
            left: 11px;
            color: #989898;
        }
        .input input{
        text-indent: 25px;
        }
        .card-text{
        font-size: 13px;
        margin-left: 6px;
        }
        .col-md-10{
        width: 50%!important;
        margin: auto;
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
      <p>Size: {{ $cartItem->size }}</p>
        <p>£{{$cartItem->price}}</p>
        <form action="{{ route('update.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
            <input type="hidden" name="size" value="{{ $cartItem->size }}">
            <p>Quantity: <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="0" max="{{$cartItem->product->quantity}}">
                <button type="submit">Update</button></p>
        </form>
            <p><a class="btn btn-outline-danger" href="{{route('remove.from.cart', $cartItem->id)}}">Delete</a></p>
              
            @endforeach
            
            <td><td><td><td data-th="Total" class="text-center"><h4>Total Price:</h4> £{{$totalPrice}}</td>
            
            <br>
            <br>
            <form action="{{ route('order') }}" method="POST">
    @csrf
    <div class="col-md-10">  
        <div class="card">
          <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header p-0">
                <h2 class="mb-0">
                  <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="d-flex align-items-center justify-content-between">
                      <span>Address</span> 
                    </div>
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body payment-card-body">
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-door-open"></i>
                        <input type="text" class="form-control" name="door_number" placeholder="Door number" maxlength="5" title="Please enter your door number" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-home"></i>
                        <input type="text" class="form-control" name="street" placeholder="Street" title="Please enter your street" required>
                      </div> 
                    </div>
                  </div>
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-building"></i>
                        <input type="text" class="form-control" name="city" placeholder="City" title="Please enter your city" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-address-card"></i>
                        <input type="text" class="form-control" name="postcode" placeholder="Postcode" maxlength="8" title="Please enter your postcode" required>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <br>
    <div class="col-md-10">  
        <div class="card">
          <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header p-0">
                <h2 class="mb-0">
                  <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="d-flex align-items-center justify-content-between">
                      <span>Payment Details</span> 
                    </div>
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body payment-card-body">
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-credit-card"></i>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" pattern="[0-9]{16}" title="Please enter a 16-digit card number" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-calendar"></i>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="Expiry - MM/YY" maxlength="5" required>
                      </div> 
                    </div>
                  </div>
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                      <i class="fas fa-eye-slash"></i>
                      <input type="text" class="form-control" id="cvc" name="cvc" placeholder="CVC - 123" maxlength="3" pattern="[0-9]{3}" title="Please enter a 3-digit CVC" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                      <i class="fas fa-user"></i>
                      <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name on Card" title="Please enter your name on your card" required>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <br>
    <button class="place-order" type="submit">Place Order</button>
</form>
        </form>
            @else
        <?php  
        $cartSession = session('cart', []);
        ?>
        @if ($cartSession)
            @foreach($cartSession as $item)
                <div class="product">
                    <img src="{{ $item['image_url'] }}" style="width: 200px; height: 200px;" class="prodImg"/></div>
                    <h3>{{ $item['name'] }}</h3>
                    <p>Size: {{ $item['size'] }}</p>
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

        <div class="col-md-10">  
        <div class="card">
          <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header p-0">
                <h2 class="mb-0">
                  <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="d-flex align-items-center justify-content-between">
                      <span>Address</span> 
                    </div>
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body payment-card-body">
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-door-open"></i>
                        <input type="text" class="form-control" name="door_number" placeholder="Door number" maxlength="5" title="Please enter your door number" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-home"></i>
                        <input type="text" class="form-control" name="street" placeholder="Street" title="Please enter your street" required>
                      </div> 
                    </div>
                  </div>
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-building"></i>
                        <input type="text" class="form-control" name="city" placeholder="City" title="Please enter your city" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-address-card"></i>
                        <input type="text" class="form-control" name="postcode" placeholder="Postcode" maxlength="8" title="Please enter your postcode" required>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <br>
    <div class="col-md-10">  
        <div class="card">
          <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header p-0">
                <h2 class="mb-0">
                  <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="d-flex align-items-center justify-content-between">
                      <span>Payment Details</span> 
                    </div>
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body payment-card-body">
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-credit-card"></i>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" pattern="[0-9]{16}" title="Please enter a 16-digit card number" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                        <i class="fas fa-calendar"></i>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="Expiry - MM/YY" maxlength="5" required>
                      </div> 
                    </div>
                  </div>
                  <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                      <div class="input">
                      <i class="fas fa-eye-slash"></i>
                      <input type="text" class="form-control" id="cvc" name="cvc" placeholder="CVC - 123" maxlength="3" pattern="[0-9]{3}" title="Please enter a 3-digit CVC" required>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="input">
                      <i class="fas fa-user"></i>
                      <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name on Card" title="Please enter your name on your card" required>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <br>
    

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