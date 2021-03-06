<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\LoginTokenMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function loginRequest(Request $request)
    {
        $request->validate(['email' => ['required', 'string', 'email', Rule::exists('users')]]);

        $user = User::byEmail($request->email)->first();

        $user->generateLoginToken();

        // Enviar correo con el link del login
        Mail::to($user)->queue(new LoginTokenMail($user));

        return back()->withSuccess('Te hemos enviado un email con el link para el login');
    }

    public function loginWithToken(Request $request)
    {
        $user = User::byEmail($request->email)->firstOrFail();

        if (Hash::check($user->login_token, $request->token)) {

            Auth::login($user);

            $request->session()->regenerate();

            $user->deleteLoginToken();

            return redirect()->intended(RouteServiceProvider::HOME)->withSuccess('Has iniciado sesi??n correctamente');
        }

        return redirect()->route('login')->withDanger('El token es inv??lido, por favor solic??talo de nuevo');
    }
}
