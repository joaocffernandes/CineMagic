<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $customer = $user->customer;
        return view('profile.edit', [
            'user' => $user,
            'customer' => $customer
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        if ($request->hasFile('photo_filename')) {
            if (
                $request->user()->photo_filename &&
                Storage::fileExists('public/photos/' . $request->user()->photo_filename)
            ) {
                Storage::delete('public/photos/' . $request->user()->photo_filename);
            }
            $path = $request->photo_filename->store('public/photos');
            $request->user()->photo_filename = basename($path);
        }


        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->save();
        }


        if ($request->user()->type == 'C') {
            $customer = Customer::where('id', $request->user()->id)->first();
            $customer->payment_type = $request->payment_type;
            $customer->nif = $request->nif;
            $customer->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->photo_filename) {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "No photo available to delete.");
        }

        $photoPath = 'public/photos/' . $user->photo_filename;
        if (!Storage::exists($photoPath)) {
            return redirect()->back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "File does not exist on the server.");
        }

        Storage::delete($photoPath);
        $user->photo_filename = null;
        $user->save();

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Photo of user {$user->name} has been deleted.");
    }
}
