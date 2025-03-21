<?php

use App\Actions\UserInvitation\UserInvitationAcceptanceAction;
use App\Models\User;
use Inertia\Inertia;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can not be rendered without an invitation token', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('invitation screen can not be rendered without a token', function () {
    $response = $this->get('/invite');

    $response->assertStatus(404);
});

test('registration screen can be rendered with valid token', function () {
    $invitation = User::factory()->staffUserInvitation()->create();

    $response = $this->get('/invite/'.$invitation->invitation_token);

    $response->assertStatus(200);
    $response->assertSee($invitation->email);
});

test('invalid token renders invalid register page', function () {
    $response = $this->get('/invite/invalid-token');

    $response->assertStatus(200);
    assert(Inertia::render('auth/InvalidRegisterToken'));
});

test('user can register with a valid invitation', function () {
    $invitation = User::factory()->staffUserInvitation()->create();
    $this->mock(UserInvitationAcceptanceAction::class)
        ->shouldReceive('execute')
        ->once()
        ->with($invitation->email, 'John Doe', 'password123!')
        ->andReturn(User::factory()->make(['email' => $invitation->email]));

    $response = $this->post('/accept', [
        'email' => $invitation->email,
        'name' => 'John Doe',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
    ]);

    $response->assertRedirect(route('dashboard'));
    expect(Auth::check())->toBeTrue();
});

test('registration fails with validation errors', function ($data, $errorField) {
    $invitation = User::factory()->staffUserInvitation()->create();

    $response = $this->post('/accept', array_merge(['email' => $invitation->email], $data));

    $response->assertSessionHasErrors($errorField);
})->with([
    [['name' => '', 'password' => 'password123!', 'password_confirmation' => 'password123!'], 'name'],
    [['name' => 'John Doe', 'password' => '', 'password_confirmation' => ''], 'password'],
    [['name' => 'John Doe', 'password' => 'password123!', 'password_confirmation' => 'wrongpassword'], 'password'],
    [['name' => 'John Doe', 'password' => 'short', 'password_confirmation' => 'short'], 'password'],
]);
