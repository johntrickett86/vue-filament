<?php

use App\Actions\UserInvitation\UserInvitationAcceptanceAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('it accepts a user invitation and updates the user record', function () {
    $user = User::factory()->create([
        'name' => null,
        'password' => null,
        'invitation_token' => Str::random(),
        'invitation_last_sent_at' => now(),
        'email_verified_at' => null,
    ]);

    $action = new UserInvitationAcceptanceAction;
    $updatedUser = $action->execute(
        email: $user->email,
        name: 'Test User',
        password: 'securePassword123',
    );

    expect($updatedUser->name)->toBe('Test User');
    expect(Hash::check('securePassword123', $updatedUser->password))->toBeTrue();
    expect($updatedUser->invitation_token)->toBeNull();
    expect($updatedUser->invitation_last_sent_at)->toBeNull();
    expect($updatedUser->email_verified_at)->not->toBeNull();
});
