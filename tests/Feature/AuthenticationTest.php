<?php

use App\Models\User;

test('a user can log in', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'invalid-password',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a user can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});

test('login view displays correctly', function () {
    $response = $this->get('/login');

    $response->assertSuccessful()
        ->assertSee('Login')
        ->assertSee('Email')
        ->assertSee('Password')
        ->assertSee('Remember me');
});
