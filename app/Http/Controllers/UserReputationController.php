<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserReputationController extends Controller
{
    public function show(User $user) {

    	return view('user', compact('user'));
    }
}
