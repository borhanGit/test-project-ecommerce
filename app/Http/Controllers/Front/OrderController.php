<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        // USER CREATE
        $password = Str::random(8);
         $userCreate = User::create([

            'name'     => $request->firstname,
            'email'    => $request->email,
            'password' => Hash::make($password),
            'address'  => $request->address,
         ]);
        
        $cartItems = \Cart::getContent();
        foreach($cartItems as $item){
            Order::Create([
                'user_id'        => $userCreate->id,
                'product_id'     => $item->id,
                'payment_method' => $request->payment_method,
                'price'          => $item->price,
                'quantity'       => $item->quantity,

            ]);
        }
        \Cart::clear();
        session()->flash('success', 'Successfully !');
        // dd($password);
        return redirect()->route('products.list');
        
    }
}
