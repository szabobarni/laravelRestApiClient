<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $response = Http::api()->post('/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (! $response->successful() || ! isset($response['user']['token'])) {
            return back()->withErrors(['email' => 'Invalid API response.']);
        }

        $user = User::updateOrCreate(
            ['email' => $response['user']['email']],
            ['name' => $response['user']['email']]
        );

        session([
            'api_token' => $response['user']['token'],
            'api_user_id' => $response['user']['id'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }




    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        session()->forget(['api_token', 'user_name', 'user_email']);
        Auth::logout();
        return redirect('/');
    }
}
