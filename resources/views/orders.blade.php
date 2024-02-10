<?php
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Orders</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <a href="{{route('home')}}">Back</a>
        <br>
        <br>
        <?php
         $user = auth()->user();
         $orders = Order::where('user_id', optional($user)->id)->get();
         ?>
         @if($orders->isNotEmpty())
         @foreach ($orders as $order) 
             <?php $orderItems = OrderItem::where('order_id', $order->id)->get(); ?>

             <h2>Order Number: {{ $order->id }}</h2>
            <table border="1">
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Address</th>
            </tr>
        @foreach ($orderItems as $item)
            <tr>
                <td>
                    <div class="col-sm-3 hidden-xs">
                        <img src="{{ $item->product->image_url }}" style="width: 200px; height: 200px;" class="card-img-top"/>
                    </div>
                </td>
                <td>{{ $item->product->name }}</td>
                <td>£{{ $item->product->price }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $order->address }}</td>
            </tr>
                @endforeach

                <tr>
                    <td colspan="5"><h4>Status:</h4> {{ $order->status }}</td>
                </tr>
                <tr>
                    <td colspan="5"><h4>Total Price:</h4> {{ $order->total_price }}</td>
                </tr>
                <tr>
                    <td colspan="5"><h4>Order Date:</h4> {{ $order->order_date }}</td>
                </tr>
            </table>
        @endforeach
        @else
        No orders placed
        @endif
    </div>
</body>
</html>