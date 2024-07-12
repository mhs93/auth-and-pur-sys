<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.index', compact('users'));
    }
}
