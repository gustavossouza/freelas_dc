<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Login realizado com sucesso!');
            }
            
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
                ]);
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Erro ao fazer login. Tente novamente.',
                ]);
        }
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Logout realizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Erro ao fazer logout.');
        }
    }

    /**
     * Get authenticated user information.
     */
    public function me(Request $request)
    {
        try {
            $user = Auth::user();
            
            return view('profile', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Erro ao carregar perfil.');
        }
    }
} 