<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Michelangelo</title>
</head>
<body>
    <h1>Edit a Category</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <form method="post" action="{{route('categories.update', ['category' => $category])}}">
        @csrf
        @method('put')
        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" value="{{$category->name}}"/>
        </div><br>
        <div>
            <label>Slug</label>
            <input type="text" name="slug" placeholder="Slug" value="{{$category->slug}}"/>
        </div><br>
        <div>
            <label>Status</label>
            <select name="status" id="status" class="form-control" value="{{$category->status}}">
                <option value="1">Active</option>
                <option value="0">Block</option></select>
        </div><br>
        <div>
            <input type="submit" value="Update" />
        </div>
    </form>
</body>
</html>