<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('staff users are redirected to the filament panel', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/staff');
    $response->assertStatus(200);
});

test('non staff users are redirected to the dashboard', function () {
    $user = User::factory()->notStaff()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
