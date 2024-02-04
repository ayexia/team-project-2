<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Michelangelo</title>
</head>
<body>
    <h1>List of Categories</h1>
    <div>
        @if(session()->has('success'))
           <div>
              {{session('success')}}
           </div>
        @endif
    </div>
    <div>
        <div>
            <a href="{{route('categories.create')}}">Create a Category</a>
        </div><br>
        <form action="/categories" method="GET">
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
                <th>Slug</th>
                <th>Status</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @if ($category->isNotEmpty())
            @foreach($category as $category)
                 <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>{{$category->slug}}</td>
                    <td>
                    @if ($category->status==1)
                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="green" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @else
                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
                    @endif
                    </td>
                    <td>
                        <a href="{{route('categories.show', ['category' => $category])}}">View</a>
                    </td>
                    <td>
                        <a href="{{route('categories.edit', ['category' => $category])}}">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('categories.destroy', ['category' => $category])}}">
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