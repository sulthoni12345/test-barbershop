<?php

use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


test('[Path 1] admin login diarahkan ke halaman services', function () {
    // Arrange — buat user dengan role admin
    $user = User::factory()->admin()->create();

    // Act — lakukan request login
    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    // Assert — kondisi IF ($role === 'admin') TRUE → redirect ke services
    $this->assertAuthenticated();
    $response->assertRedirect(route('services.index'));
});

test('[Path 2] kasir login diarahkan ke halaman transactions', function () {
    // Arrange — buat user dengan role kasir
    $user = User::factory()->kasir()->create();

    // Act — lakukan request login
    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    // Assert — kondisi IF ($role === 'admin') FALSE → redirect ke transactions
    $this->assertAuthenticated();
    $response->assertRedirect(route('transactions.index'));
});
