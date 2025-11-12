<?php

use App\Livewire\Auth\Register;
use Livewire\Livewire;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register');

    $response
        ->assertHasNoErrors()
        // ⭐ PERBAIKAN: User baru (Customer) diarahkan ke route 'home' (yaitu '/')
        ->assertRedirect(route('home', absolute: false)); // Ganti 'dashboard' menjadi 'home'

    $this->assertAuthenticated();
});