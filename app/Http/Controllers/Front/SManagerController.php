<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\sManagerService;
use Illuminate\Http\Request;
use Session;
use Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SManagerController extends Controller
{
    public function index(Request $request)
    {
        if($request->payment_method == 1){
            try{
                  // USER CREATE
                     $password     =  Str::random(8);
                     $userCreate   =  User::Create([
                                    'name'     => $request->firstname,
                                    'email'    => $request->email,
                                    'phone'    => $request->phone,
                                    'password' => Hash::make($password),
                                    'address'  => $request->address,
                                 ]);
                    $userCreate->roles()->sync(2);
                    if($userCreate->id){
                        $orderStore = Order::Create([
                            'user_id'        => $userCreate->id,
                            'payment_method' => $request->payment_method,                            
                        ]);
                        if($orderStore->id){
                            $cartItems = \Cart::getContent();
                            foreach($cartItems as $item){
                                $orderDetailsStore = OrderDetail::Create([
                                    'order_id'   => $orderStore->id,
                                    'product_id' => $item->id,
                                    'price'      => $item->price,
                                    'quantity'   => $item->quantity,
                                    'total'      => $item->price * $item->quantity,
                                ]);
                           
                            }
                            
                        }
                        \Cart::clear();
                        session()->flash('success', 'Successfully !');
                        return redirect()->route('products.list');
                    }
            }  catch (\Exception $ex) {
                session()->flash('error', $ex->getMessage());
                return redirect()->back();
            }

        }
        else if($request->payment_method == 2){
          
            $post_data = array();
            $post_data['total_amount'] = \Cart::getTotal(); 
            $post_data['currency'] = "BDT";
            $post_data['transaction_id'] = uniqid('sM_', true); 

            $info = [
                'amount'          => $post_data['total_amount'],
                'transaction_id'  => $post_data['transaction_id'],
                'success_url'     => route('smanager.success'),  // success url
                'fail_url'        => route('smanager.fail'),  // failed url
                'customer_name'   => $request->firstname,
                'customer_mobile' => $request->phone,
                'purpose'         => 'Online Payment',
                'payment_details' => 'Paying for '.\Cart::getTotalQuantity().' items',
            ];
            session()->put('sM_transaction_id', $post_data['transaction_id']);
            session()->put('firstName', $request->firstname);
            session()->put('email', $request->email);
            session()->put('phone', $request->phone);
            session()->put('address', $request->address);
            session()->put('payment_method', $request->payment_method);
    
            return sManagerService::initiatePayment($info);

        }

    }

    public function success(Request $request)
    {

        try{
            // USER CREATE
               $password     =  Str::random(8);
               $userCreate   =  User::Create([
                              'name'     => session()->get('firstName'),
                              'email'    => session()->get('email'),
                              'phone'    => session()->get('phone'),
                              'password' => Hash::make($password),
                              'address'  => session()->get('address'),
                           ]);
             $userCreate->roles()->sync(2);
              if($userCreate->id){
                  $orderStore = Order::Create([
                      'user_id'        => $userCreate->id,
                      'payment_method' => session()->get('payment_method'),                            
                      'transaction_id' => session()->get('sM_transaction_id'),                            
                  ]);
                  if($orderStore->id){
                      $cartItems = \Cart::getContent();
                      foreach($cartItems as $item){
                          $orderDetailsStore = OrderDetail::Create([
                              'order_id'   => $orderStore->id,
                              'product_id' => $item->id,
                              'price'      => $item->price,
                              'quantity'   => $item->quantity,
                              'total'      => $item->price * $item->quantity,
                          ]);
                     
                      }
                      
                  }
                  
                  \Cart::clear();
                  session()->forget('firstName');
                  session()->forget('email');
                  session()->forget('phone');
                  session()->forget('address');
                  session()->forget('payment_method');
                  session()->flash('success', 'Payment Completed');
                  return redirect()->route('products.list');
              }
      }  catch (\Exception $ex) {
          session()->flash('error', $ex->getMessage());
          return redirect()->route('products.list');
          
      }

       
     
    }

    public function fail(Request $request)
    {   
        session()->forget('sM_transaction_id');
        session()->flash('error', 'Payment Failed');
        return redirect()->route('products.list');
    }


}
