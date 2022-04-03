<?php

namespace App\Services;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Проверка на существования пользователя с такими данными
     * @param Request $request - объект с данными
     * @return bool|Builder|Model|object|null
     */
    public function checkCreateUser(Request $request)
    {
        if ($request->input('email') === null) {
            return Auth::attempt($request->all());
        } else {
            return User::query()
                ->where('phone_number', '=', $request->input('phone_number'))
                ->orWhere('email', '=' . $request->input('email'))
                ->first();
        }
    }

    /**
     * Регистрация пользователя
     * @param Request $request - объект с данными
     * @return bool
     */
    public function signUp(Request $request)
    {
        $user = User::create([
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'stuff' => false
        ]);

        $user->roles()->attach(Roles::where('slug', 'student')->first());
        $user->save();

        return true;
    }

    /**
     * Авторизация пользователя и создание куки-файла
     * @return array
     */
    public function signIn()
    {
        $user = Auth::user();
        $role = auth('sanctum')->user()->roles[0]->slug;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24 * 7); // 7 day

        return [
            'role' => $role,
            'cookie' => $cookie
        ];
    }
}
