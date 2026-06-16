<?php

use App\Models\User;

// --- Kelas Valid ---

test('[EP-E1] login dengan email dan password valid sebagai kasir berhasil', function () {
    $user = User::factory()->kasir()->create();

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('transactions.index'));
});

test('[EP-E2] login dengan email dan password valid sebagai admin berhasil', function () {
    $user = User::factory()->admin()->create();

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('services.index'));
});

test('[EP-P1] password benar menghasilkan autentikasi berhasil', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
});

// --- Kelas Invalid ---

test('[EP-E3] email kosong menghasilkan error validasi', function () {
    $response = $this->post('/login', [
        'email'    => '',
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email']);
});

test('[EP-E4] format email tidak valid menghasilkan error validasi', function () {
    $response = $this->post('/login', [
        'email'    => 'bukanformat-email',
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email']);
});

test('[EP-E5] email tidak terdaftar menghasilkan error kredensial', function () {
    $response = $this->post('/login', [
        'email'    => 'tidakada@email.com',
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('[EP-P2] password kosong menghasilkan error validasi', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => '',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['password']);
});

test('[EP-P3] password salah menghasilkan error kredensial', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'passwordSalah999',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});
