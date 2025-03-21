<?php

namespace App\Actions\UserInvitation;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;

class UserInvitationAcceptanceAction
{
    public function execute(string $email, string $name, string $password): User
    {
        $user = User::where('email', $email)->firstOrFail();

        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => CarbonImmutable::now(),
            'invitation_token' => null,
            'invitation_last_sent_at' => null,
        ]);

        return $user;
    }
}
