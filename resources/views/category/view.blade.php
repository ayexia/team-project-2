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

    <body>
    <h1>Category details</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <div>
            <a href="{{route('categories.index')}}">Back</a>
        </div>
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
            </tr>
                 <tr>
                    <td>{{$category->name}}</td>
                 </tr>
        </table>
    </div>
    <script>
    //zoom in feature
    const productImage = document.getElementById('productImage');
    const zoomImage = document.getElementById('zoomImage');
    productImage.addEventListener('mousemove', function(e) {
       const { left, top, width, height } = productImage.getBoundingClientRect();
        const mouseX = e.pageX - left;
        const mouseY = e.pageY - top;

  
    const percentX = (mouseX / width) * 100;
    const percentY = (mouseY / height) * 100;


    zoomImage.style.transformOrigin = `${percentX}% ${percentY}%`;
    zoomImage.style.transform = 'scale(3.0)'; 
    });


    productImage.addEventListener('mouseleave', function() {
    zoomImage.style.transform = 'scale(1)';
    });

    //scroll to top button feature
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");

    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollToTopBtn.style.display = "block";
        } else {
        scrollToTopBtn.style.display = "none";
        }
    };

    scrollToTopBtn.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
        behavior: "smooth"
        });
    }); 

    //scroll to top button feature
const scrollToTopBtn = document.getElementById("scrollToTopBtn");


window.onscroll = function() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }
};

scrollToTopBtn.addEventListener("click", function() {
  
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});
    </script>

    <style>
        /*  zoom feature */
   .product-image {
    width: 50%; /* Adjust as needed */
    margin-right: 20px;
    overflow: hidden;
  }
  
  #zoomImage {
    width: 100%; /* Make sure the image fills its container */
    transition: transform 0.5s; /* Smooth transition for zoom effect */
  }
  /*scroll*/
  #scrollToTopBtn {
    position: fixed;
    bottom: 210px;
    left: 100px;
    z-index: 99;
    display: none; 
    /*  button styles */
    background-color: #008080;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
  }
  
  #scrollToTopBtn:hover {
    background-color: #006666;
  }
    </style>
</body>
</html>