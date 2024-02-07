<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Product details</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <div>
            <a href="{{route('product.index')}}">Back</a>
        </div>
        <br>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Colour</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Category</th>
            </tr>
                 <tr>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->price}}</td>
                    <td><img src="{{$product->image_url}}" style="width: 200px; height: 200px;"></td>
                    <td>{{$product->colour}}</td>
                    <td>{{$product->brand}}</td>
                    <td>{{$product->size}}</td>
                    <td>{{ $product->category->name }}</td>
                 </tr>
        </table>
                        
        <br><br><p class="btn-holder"><a href="{{ route('add.to.cart', $product->id) }}" class="btn btn-outline-danger">Add to cart</a> </p>
    </div>
</body>
</html>