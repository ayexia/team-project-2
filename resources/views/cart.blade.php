<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Cart</h1>
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
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
        @if(session('cart'))
            @php
            $totalPrice = 0;
            @endphp
            @foreach(session('cart') as $item => $details)
                <tr rowId="{{ $item }}">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{ $details['image'] }}" style="width: 200px; height: 200px;" class="card-img-top"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">£{{ $details['price'] }}</td>
                    <td data-th="Quantity">{{ $details['quantity'] }}</td>
                    @php
                    $subtotal = $details['price'] * $details['quantity'];
                    $totalPrice += $subtotal;
                    @endphp
                    <td data-th="Total" class="text-center">£{{$totalPrice}}</td>
                    <td class="actions">
                        <a class="btn btn-outline-danger btn-sm delete-product"><i class="fa fa-trash-o"></i></a>
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