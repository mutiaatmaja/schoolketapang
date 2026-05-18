<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterParentRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredParentController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterParentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $parentRole = Role::query()->firstOrCreate(
            ['name' => 'orang_tua'],
            [
                'display_name' => 'Orang Tua',
                'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.',
            ],
        );

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->syncRoles([$parentRole->name]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('ppdb.daftar');
    }
}
