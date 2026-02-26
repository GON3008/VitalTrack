<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'phone'    => $data['phone'] ?? null,
        ]);

        $user->healthProfile()->create([
            'date_of_birth' => null,
            'gender'        => null,
        ]);

        $token = JWTAuth::login($user);

        return [
            'user'  => $user,
            'token' => $this->buildToken($token),
        ];
    }

    public function login(array $credentials): array|false
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        return [
            'user'  => JWTAuth::user(),
            'token' => $this->buildToken($token),
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return $this->buildToken($token);
    }

    public function me(): User
    {
        return JWTAuth::user();
    }

    private function buildToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
        ];
    }
}
