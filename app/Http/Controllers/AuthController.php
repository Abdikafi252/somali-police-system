<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Forgot Password - Show form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Forgot Password - Send reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email-kan kuma jiro nidaamka. (This email does not exist in the system.)',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Emailka dib u dejinta erayga sirta ah ayaa lagu soo diray! (Password reset link has been sent to your email!)')
            : back()->withErrors(['email' => 'Wax khalad ah ayaa dhacay. Fadlan isku day mar kale. (Something went wrong. Please try again.)']);
    }

    // Reset Password - Show form
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Reset Password - Process reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Erayga sirta ah waa inuu ka badan yahay 8 xaraf. (Password must be at least 8 characters.)',
            'password.confirmed' => 'Erayada sirta ah ma isku mid aha. (Passwords do not match.)',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Erayga sirta ah si guul leh ayaa loo beddelay! (Password has been reset successfully!)')
            : back()->withErrors(['email' => 'Wax khalad ah ayaa dhacay. Fadlan isku day mar kale. (Something went wrong. Please try again.)']);
    }
}
