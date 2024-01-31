<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Create a Product</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>



        @endif
    </div>
    <form method="post" action="{{route('product.store')}}">
        @csrf
        @method('post')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" />
        </div><br>
        <div>
            <label>Description</label>
            <input type="text" name="description" placeholder="Description" />
        </div><br>
        <div>
            <label>Price</label>
            <input type="text" name="price" placeholder="Price" />
        </div><br>
        <div>
            <label>Quantity</label>
            <input type="text" name="quantity" placeholder="Quantity" />
        </div><br>
        <div>
            <label>Image URL</label>
            <input type="text" name="image url" placeholder="Image URL" />
        </div><br>
        <div>
            <label>Colour</label>
            <input type="text" name="colour" placeholder="Colour" />
        </div><br>
        <div>
            <label>Brand</label>
            <input type="text" name="brand" placeholder="Brand" />
        </div><br>
        <div>
            <label>Size</label>
            <input type="text" name="size" placeholder="Size" />
        </div><br>
        <div>
            <label>Category</label>
            <input type="text" name="category" placeholder="Category" />
        </div><br>
        <div>
            <input type="submit" value="Save a New Product" />
        </div>
    </form>
</body>
</html>