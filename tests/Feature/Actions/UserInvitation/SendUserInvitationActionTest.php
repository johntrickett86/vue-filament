<?php

use App\Actions\UserInvitation\SendUserInvitationAction;
use App\Mail\UserInvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

test('it sends a user invitation and sets token and timestamp', function () {
    Mail::fake();

    $user = User::factory()->create([
        'invitation_token' => null,
        'invitation_last_sent_at' => null,
    ]);

    $action = new SendUserInvitationAction;
    $action->execute($user);

    $user->refresh();

    expect($user->invitation_token)->not->toBeNull()
        ->and(Str::length($user->invitation_token))->toBeGreaterThan(0)
        ->and($user->invitation_last_sent_at)->not->toBeNull();

    Mail::assertQueued(UserInvitationMail::class, fn ($mail) => $mail->hasTo($user->email));
});
