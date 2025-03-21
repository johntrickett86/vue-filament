<?php

namespace App\Actions\UserInvitation;

use App\Mail\UserInvitationMail;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendUserInvitationAction
{
    public function execute(User $user): void
    {
        $token = Str::random();

        $user->update([
            'invitation_token' => $token,
        ]);

        Mail::to($user->email)->send(new UserInvitationMail($user));

        $user->update([
            'invitation_last_sent_at' => CarbonImmutable::now(),
        ]);
    }
}
