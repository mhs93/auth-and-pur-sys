<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Mail\OTPMail;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::all();

        if ($user->is_admin) {
            return view('admin.index', ['admin' => $user]);
        }
        return view('user.index', compact('user', 'products'));
    }


    public function RegistrationForm()
    {
        return view('user.register');
    }

    public function userRegister(UserRegisterRequest $request)
    {
        try {
            $otp = rand(100000, 999999);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => false,
                'password' => Hash::make($request->password),
                'otp' => $otp,
            ]);
            Mail::to($request->email)->send(new OTPMail($otp, $request->name));

            return view('user.otp-verify', ['email' => $request->email]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function VerifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where('email', $email)->first(['otp']);

        if ($otp != $user->otp) {
            return redirect()->back()->withErrors(['otp' => 'OTP does not match']);
        }
        $user->update(['otp' => null]);
        Session::put('user', $user);
        return redirect()->route('home')->with('user', $user);
    }

    public function LoginPage()
    {
        return view('user.login');
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();
            $request->session()->put('user', $user);
            $products = Product::all();
            if ($user->is_admin) {
                $users = User::where('is_admin', false)->get();

                return view('admin.index', ['users' => $users]);
            } else {
                return view('user.index', ['user' => $user, 'products' => $products]);
            }
        }
        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
