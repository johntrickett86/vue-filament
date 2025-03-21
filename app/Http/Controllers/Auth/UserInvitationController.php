<?php

namespace App\Http\Controllers\Auth;

use App\Actions\UserInvitation\UserInvitationAcceptanceAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserInvitationController extends Controller
{
    public function show(string $token): Response
    {
        if (! $user = User::where('invitation_token', $token)->first()) {
            return Inertia::render('auth/InvalidRegisterToken');
        }

        return Inertia::render('auth/Register', [
            'email' => $user->email,
        ]);
    }

    public function store(Request $request, UserInvitationAcceptanceAction $userInvitationAcceptanceAction): RedirectResponse
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');

        $user = $userInvitationAcceptanceAction->execute($email, $name, $password);

        Auth::login($user);

        return to_route('dashboard');
    }
}
