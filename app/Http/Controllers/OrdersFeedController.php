<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class OrdersFeedController extends Controller
{
    public function index() {
    	$orders = Order::with(['user', 'user.detail', 'postedBids'])
    		->posted()
    		->sameBarangay()
    		->latest()
    		->paginate(10);
    	return view('orders_feed', compact('orders'));
    }
}
