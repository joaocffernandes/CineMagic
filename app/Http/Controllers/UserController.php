<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(): View
    {
        $allUsers = User::orderBy('type')
            ->orderBy('name')
            ->where('type', '!=', 'C')
            ->paginate(20);


        return view('users.index')->with('allUsers', $allUsers);
    }

    public function create(): View
    {
        $newUser = new User();
        return view('users.create')->with('user', $newUser);
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8', // Adicione a regra para o campo de confirmação
            'photo_filename' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'type' => 'required|string|in:A,E',
        ]);

        
        if ($request->hasFile('photo_filename')) {
            $path = $request->photo_filename->store('public/photos');
            $validated['photo_filename'] = basename($path);
        }else{
            $path = NULL;
            $validated['photo_filename'] = $path;
        }

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
            'photo_filename' => $validated['photo_filename']
        ]);

        
        $url = route('users.show', ['user' => $newUser]);
        $htmlMessage = "User <a href='$url'><u>{$newUser->name}</u></a> ({$newUser->email}) has been created successfully!";
        return redirect()->route('users.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $user): View
    {
        return view('users.edit')->with('user', $user);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        // Validar os dados recebidos
        $validatedData = $request->validated();

        if ($request->hasFile('photo_filename')) {
            if (
                $user->photo_filename &&
                Storage::fileExists('public/photos/' . $user->photo_filename)
            ) {
                Storage::delete('public/photos/' . $user->photo_filename);
            }
            $path = $request->photo_filename->store('public/photos');
            $validatedData['photo_filename'] = basename($path);
        }

        // Atualizar a senha se for fornecida
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            // Remover a senha se não foi fornecida
            unset($validatedData['password']);
        }

        // Atualizar os dados do usuário
        $user->fill($validatedData);
        $user->save();

        // Verificar se o usuário foi atualizado corretamente
        if ($user->wasChanged()) {
            // Usuário atualizado com sucesso
            $url = route('users.show', ['user' => $user]);
            $htmlMessage = "User <a href='$url'><u>{$user->name}</u></a> ({$user->email}) has been updated successfully!";
            return redirect()->route('users.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', $htmlMessage);
        } else {
            // Falha na atualização do usuário
            return back()->withInput()->withErrors(['error' => 'Failed to update user.']);
        }
    }




    public function show(User $user): View
    {
        return view('users.show')->with('user', $user);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('user', $user);;
    }

    public function destroyPhoto(User $user){

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

    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = !$user->blocked;
        $user->save();
        return redirect()->route('customers');
    }

    public function customers(Request $request): View
    {
        $query = User::where('type', '=', 'C')
            ->orderBy('type')
            ->orderBy('name');

        // Check if a name filter has been provided and apply it
        $filterByName = $request->query('name');
        if (!empty($filterByName)) {  // You can use !empty() to check if it's not null or empty
            $query->where('name', 'like', "%" . $filterByName . "%");
        }

        // Execute the query with pagination
        $customers = $query->paginate(20);

        return view('users.customers', [
            'customers' => $customers,
            'filterByName' => $filterByName
        ]);
    }

    public function destroyCustomers($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('customers');
    }
}
