<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Show the user profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = Auth::user();
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return back()->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar perfil. Tente novamente.');
        }
    }

    /**
     * Update the user password.
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $user = Auth::user();
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'A senha atual está incorreta.']);
            }
            
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return back()->with('success', 'Senha alterada com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao alterar senha. Tente novamente.');
        }
    }

    /**
     * Delete the user account.
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Verify password before deletion
            if (!Hash::check($request->password, $user->password)) {
                return back()
                    ->withErrors(['password' => 'A senha está incorreta.']);
            }
            
            // Logout and delete user
            Auth::logout();
            $user->delete();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Conta excluída com sucesso.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erro ao excluir conta. Tente novamente.');
        }
    }
} 