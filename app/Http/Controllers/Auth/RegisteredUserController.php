<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'id_role' => $request->id_role,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_departmen' => $request->id_departmen,
            'id_fakultas' => $request->id_fakultas,
            'name' => $request->name,
            'foto_url' => $request->foto_url,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Auth::login($user);

        return response()->json([
            'message' => 'Berhasil Membuat Akun Baru',
            'data' => $user
        ], 200);
    }
}
