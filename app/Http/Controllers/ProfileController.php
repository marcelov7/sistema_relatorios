<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Exibir o perfil do usuário
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('profile.show', compact('user'));
    }

    /**
     * Atualizar informações do perfil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9._-]+$/'],
            'departamento' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'username.unique' => 'Este nome de usuário já está sendo usado.',
            'username.regex' => 'O nome de usuário pode conter apenas letras, números, pontos, hífens e sublinhados.',
            'departamento.max' => 'O departamento não pode ter mais de 255 caracteres.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'departamento' => $request->departamento,
        ]);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualizar senha do usuário
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('A senha atual está incorreta.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'password.required' => 'A nova senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Senha alterada com sucesso!');
    }
} 