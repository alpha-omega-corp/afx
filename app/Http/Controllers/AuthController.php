<?php

namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect()->route('admin.pages');
        }

        return redirect()->route(__('route.home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Both, not just the first: invalidate() drops the session data the
        // old id still points at, and regenerateToken() issues the CSRF token
        // the next form post from this tab will be checked against.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route(__('route.home'));
    }
}
