<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
{
    {
        $lowStockProducts = Product::where('quantity', '<', 5)->get();
        $users = User::all();
        $selectedPeriod = request('period', 'monthly');
        $orders = $this->getOrdersForPeriod($selectedPeriod);
        $allOrders = Order::all();
    
        return view('admin.adminhome', compact('lowStockProducts', 'users', 'orders', 'selectedPeriod', 'allOrders'));
    }
}

    private function getOrdersForPeriod($period)
    {
        $orders = null;
        if ($period === 'monthly') {
            $orders = Order::whereMonth('created_at', now()->month)->get();
        } elseif ($period === 'weekly') {
            $orders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
        }
        return $orders;
    }

    public function changeUserType(User $user)
    {
        if ($user->usertype === 'admin') {
            $user->usertype = 'user';
        } else {
            $user->usertype = 'admin';
        }
        $user->save();
        return redirect()->back()->with('success', 'User type changed successfully.');
    }
}    

