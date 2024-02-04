<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Products</h1>
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
    <br><table border="1">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Colour</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Category</th>
                <th>View</th>
            </tr>
            @if ($products->isNotEmpty())
            @foreach($products as $product)
            @if ($product->available === 'yes')
                 <tr>
                    <td>{{$product->name}}</td>
                    <td>{{$product->price}}</td>
                    <td><img src="{{$product->image_url}}" alt="Product Image" style="width: 200px; height: 200px;"></td>
                    <td>{{$product->colour}}</td>
                    <td>{{$product->brand}}</td>
                    <td>{{$product->size}}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        <a href="{{route('product.show', ['product' => $product])}}">View</a>
                    </td>
                 </tr>
            @endif
            @endforeach
        </table>        
        @else
            Records not found
        @endif
    </div>
</body>
</html>