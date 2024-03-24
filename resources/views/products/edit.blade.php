<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="authors" content="Ayesha, Nagina">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Edit a Product</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <form method="post" action="{{route('product.update', ['product' => $product])}}">
        @csrf
        @method('put')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" value="{{$product->name}}"/>
        </div><br>
        <div>
            <label>Description</label><br>
            <textarea name="description" cols="30" rows="10" placeholder="Description"></textarea>
        </div><br>
        <div>
            <label>Price</label>
            <input type="text" name="price" placeholder="Price" value="{{$product->price}}"/>
        </div><br>
        <div>
            <label>Quantity</label>
            <input type="text" name="quantity" placeholder="Quantity" value="{{$product->quantity}}"/>
        </div><br>
        <div>
            <label>Image URL</label>
            <input type="text" name="image url" placeholder="Image URL" value="{{$product->image_url}}"/>
        </div><br>
        <div>
            <label>Colour</label>
            <input type="text" name="colour" placeholder="Colour" value="{{$product->colour}}"/>
        </div><br>
        <div>
            <label>Brand</label>
            <input type="text" name="brand" placeholder="Brand" value="{{$product->brand}}"/>
        </div><br>
        <div id="sizeInputs">
            <label>Sizes</label><br>
            @if ($product->sizes)
            @foreach(json_decode($product->sizes) as $size)
                <input type="text" name="sizes[]" placeholder="Size" value="{{ $size }}" /><br>
            @endforeach
            <button type="button" onclick="addSizeField()">Add Size</button>
            @endif
        </div><br>
        <div>
          <select name="category_id" id="category_id" class="form-control">
            <option value ="">Category</option>
          @if ($category->isNotEmpty())
            @foreach ($category as $category)
            <option value="{{$category->id}}">{{ $category->name }}</option>
            @endforeach
          @endif 
          </select>
        </div><br>
        <div class="form-group">
            <label for="available">Available?</label>
            <select name="available" id="available" class="form-control">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
            </select>
        </div><br>
        <div>
            <input type="submit" value="Update" />
        </div>
    </form>
    <script>
        function addSizeField() {
            const sizeInputs = document.getElementById('sizeInputs');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'sizes[]';
            input.placeholder = 'Size';
            sizeInputs.appendChild(input);
            sizeInputs.appendChild(document.createElement('br'));
        }
    </script>
</body>
</html>