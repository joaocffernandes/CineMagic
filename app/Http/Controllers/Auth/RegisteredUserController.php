<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Customer;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $user = new User();
        return view('auth.register')->with('user', $user);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'photo_filename' => 'sometimes|image|mimes:jpeg,png,jpg|max:4096',
            'nif' => 'required|digits:9',
            'payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',
        ];


        if ($request->payment_type == 'MBWAY'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^9\d{8}$/',
            ]);
        }

        if ($request->payment_type == 'PAYPAL'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|string|email|max:255',
            ]);
        }

        if ($request->payment_type == 'VISA'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^4[0-9]{15}$/',
            ]);
        }

        $request->validate($rules);

        if ($request->hasFile('photo_filename')) {
            $path = $request->photo_filename->store('public/photos');
        }else{
            $path = NULL;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'C',
            'photo_filename' => basename($path)
        ]);
        
        event(new Registered($user));

        $customer = Customer::create([
            'id' => $user->id,
            'nif' => $request->nif,
            'payment_type'=> $request->payment_type,
            'payment_ref' => $request->payment_ref
        ]);

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
