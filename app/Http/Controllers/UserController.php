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
    public function showCase(): View
    {
        return view('users.showcase');
    }

    public function create(): View
    {
        $newUser = new User();
        return view('users.create')->with('user', $newUser);
    }


    public function store(UserRequest $request): RedirectResponse
    {
        $newUser = User::create($request->validated());

        if ($request->hasFile('image_file')) {
            $request->image_file->storeAs('public/users', $newUser->fileName);
        }

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

    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = !$user->blocked;
        $user->save();

        return redirect()->route('users.index')->with('users', $users);;
    }
}
