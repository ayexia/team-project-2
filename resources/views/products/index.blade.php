<?php
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Order;
$user = auth()->user();
$count = 0;
if ($user) {
    $cart = Cart::where('user_id', optional($user)->id)->first();
    if($cart){
    $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        }
    } else {
        $cartSession = session('cart', []);    
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
        <title>ML Menswear - Categories</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/websiteStyle.css') }}">   
    </head>

    <header>
        <div class="logo">
            <a href="{{ url('/') }}">
            <img src="{{ asset('images/ML Menswear Logo.JPG') }}" alt="ML Menswear Logo">            </a>
        </div>
        <?php
            $user = auth()->user();
        ?>
        <div class="search-bar">
            <form action="/product" method="GET">
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
 
    <body>
        <h1>List of Products</h1>
        <div>
            @if(session()->has('success'))
            <div>
              {{session('success')}}
            </div>
            @endif
        </div>
        <div>
            <a href="{{route('home')}}">Back</a>
        </div>
        <br>
        <div>
            <a href="{{route('product.create')}}">Create a Product</a>
        </div><br>
        <form action="/product" method="GET">
            <div class="input-group" style="width: 250px;">
            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="float-right" placeholder="Search">
                <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                    Submit
                </button>
                </div>
            </div>
        </form>
        <br>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image URL</th>
                <th>Colour</th>
                <th>Brand</th>
                <th>Sizes</th>
                <th>Category</th>
                <th>Available?</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @if ($products->isNotEmpty())
            @foreach($products as $product)
                 <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->image_url}}</td>
                    <td>{{$product->colour}}</td>
                    <td>{{$product->brand}}</td>
                    <td>                    
                    @if ($product->sizes)
                            @foreach(json_decode($product->sizes) as $size)
                                {{$size}}
                                @unless ($loop->last)
                                    ,
                                @endunless
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{$product->available}}</td>
                    <td>
                        <a href="{{route('product.show', ['product' => $product])}}">View</a>
                    </td>
                    <td>
                        <a href="{{route('product.edit', ['product' => $product])}}">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('product.destroy', ['product' => $product])}}">
                            @csrf
                            @method('delete')
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                 </tr>
            @endforeach
        </table>        
        @else
            Records not found
        @endif
        </div>
    </body>
</html>