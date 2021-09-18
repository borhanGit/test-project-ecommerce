@extends("layouts.frontend")
@section('content')
<div class="row">
    <div class="col-75">
      <div class="container">
        <form action="{{ route('order.store') }}" method="POST">
          @csrf
  
          <div class="row">
            <div class="col-50">
              <h3>Billing Address</h3>
              <label for="fname"><i class="fa fa-user"></i> Full Name</label>
              <input type="text" id="fname" name="firstname" placeholder="John M. Doe" required>
              <label for="email"><i class="fa fa-envelope"></i> Email</label>
              <input type="email"  id="email" name="email" placeholder="john@example.com" required>
              <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
              <input type="text" id="adr" name="address" placeholder="542 W. 15th Street" required>
  
              <div class="row">
                <div class="col-50">
                  <label for="state">Payment Method </label>
                  <select  name="payment_method" id="" class="form-control select-option" required>
                    <option value="">Select Payment Method</option>
                    <option value="cash">Cash On</option>
                    <option value="online">Online</option>
                </select>
                  {{-- <input type="text" id="state" name="state" placeholder="NY"> --}}
                </div>
               
              </div>
            </div>
  
          </div>
          
          <input type="submit" value="Continue to checkout" class="btn">
        
      </div>
    </div>
  
    <div class="col-25">
      <div class="container">
        <h4>Cart
          <span class="price" style="color:black">
            <i class="fa fa-shopping-cart"></i>
            <b> {{ Cart::getTotalQuantity()}}</b>
          </span>
        </h4>
        @foreach ($cartItems as $item)
        <p><a href="#">{{ $item->name }}</a> <span class="price">{{ $item->price }} TK.</span></p>
            
        @endforeach
       
       
        <hr>
        <p>Total <span class="price" style="color:black"><b>TK. {{ Cart::getTotal() }}</b></span></p>
      </div>
    </div>
  </form>
  </div>
@endsection