<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Menswear Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/websiteStyle.css') }}">   
    <style>
        body { background-color: #E5E7EB; } /* Grey background */
        .header { background-color: #008080; } /* Teal header */
        .sidebar { background-color: #1F2937; } /* Black sidebar */
        .card { background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        .sidebar ul li {
            margin-bottom: 5px; 
        }
        .sidebar ul li a {
            display: block; 
            padding: 8px 0; 
        }    
    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <div class="sidebar p-5 text-white w-1/4">
        <h2 class="text-lg font-bold mb-5">ML Admin Dashboard</h2>
        <ul>
            <li><a href="{{route('home')}}">Home</a></li>
            <li><a href="{{route('product.index')}}">Products</a></li>
            <li><a href="{{route('categories.index')}}">Categories</a></li>
            <li><a href="#stock-alerts">Stock Alerts</a></li>
            <li><a href="#user-management">User Management</a></li>
            <li><a href="#sales-by-period">Sales by Period</a></li>
            <li><a href="#order-management">Order Management</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="flex-1 p-10">
        <div class="header p-5 text-white">
            <h1 class="text-xl"></h1>
        </div>
        
        <!-- Dashboard Widgets -->
        <div class="grid gap-4 mt-5">
            <!-- Stock Alert Widget -->
            <div id="stock-alerts" class="card p-5">
                <h2 class="font-bold text-lg">Stock Alerts</h2>
                @if ($lowStockProducts->count() > 0)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <p>The following products have low stock:</p>
                        <ul>
                            @foreach ($lowStockProducts as $product)
                                <li>{{ $product->name }} ({{ $product->quantity }} left)</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p>No products have low stock.</p>
                @endif
            </div>
            
            <!-- User Management Widget -->
            <div id="user-management" class="card p-5">
                <h2 class="font-bold text-lg mb-3">User Management</h2>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">User Type</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->usertype }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('admin.changeUserType', $user) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-teal-500 text-black rounded p-2">
                                            @if ($user->usertype === 'admin')
                                                Make User
                                            @else
                                                Make Admin
                                            @endif
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sales by Period Widget -->
            <div id="sales-by-period" class="card p-5">
                <h2 class="font-bold text-lg mb-3">Sales by Period</h2>
                <form action="{{ route('admin.dashboard') }}" method="GET">
                    <label for="period" class="block mb-2">Select Period:</label>
                    <select id="period" name="period" class="border-2 border-gray-200 rounded p-2">
                        <option value="monthly" {{ $selectedPeriod === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="weekly" {{ $selectedPeriod === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                    <button type="submit" class="bg-teal-500 text-black rounded p-2 mt-2">View Sales</button>
</form>
@if ($orders->count() > 0)
    <table class="table-auto w-full mt-4">
        <thead>
            <tr>
                <th class="px-4 py-2">Order ID</th>
                <th class="px-4 py-2">Total Amount</th>
                <th class="px-4 py-2">Order Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->total_price }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="mt-4">No orders found for the selected period.</p>
@endif
</div>

<!-- Order Management -->
<div id="order-management" class="card p-5">
    <h2 class="font-bold text-lg mb-3">Order Management</h2>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Order ID</th>
                <th class="px-4 py-2">Total Amount</th>
                <th class="px-4 py-2">Order Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allOrders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->total_price }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2">{{ $order->status }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex">
                            @if ($order->status === 'Pending')
                                <form action="{{ route('admin.processingOrder', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-teal-500 text-black rounded p-2 mr-2">Mark as Processing</button>
                                </form>
                            @elseif ($order->status === 'Processing')
                                <form action="{{ route('admin.shippedOrder', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-teal-500 text-black rounded p-2 mr-2">Mark as Shipped</button>
                                </form>
                            @elseif ($order->status === 'Shipped')
                                <form action="{{ route('admin.deliveredOrder', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-teal-500 text-black rounded p-2 mr-2">Mark as Delivered</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</body>
</html>