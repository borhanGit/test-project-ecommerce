<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_order'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orders = Order::with('orderDetails','user')->get();
        foreach ($orders  as $index => $order) {            
            $orders[$index]['amount'] = OrderDetail::where('order_id',$order->id)->sum('total');
            $orders[$index]['quantity'] = OrderDetail::where('order_id',$order->id)->sum('quantity');
           
        }
        return view('admin.orders.index', compact('orders'));
    }
}
