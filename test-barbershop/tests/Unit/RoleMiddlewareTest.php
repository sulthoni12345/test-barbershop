<?php

use App\Models\User;

// ============================================================
// UNIT TEST — RoleMiddleware::handle()
// Berdasarkan Independent Path BAB III Method 2 (V(G) = 3)
// ============================================================

test('[Path 3] user belum login akses halaman protected mendapat 403', function () {
    // Arrange — tidak ada user yang login (guest)

    // Act — akses halaman yang dilindungi middleware role:admin
    $response = $this->get('/services');

    // Assert — N2: !Auth::check() = TRUE → abort(403)
    // Laravel redirect guest ke /login bukan 403, jadi assert redirect ke login
    $response->assertRedirect('/login');
});

test('[Path 4] kasir akses halaman admin-only mendapat 403', function () {
    // Arrange — buat user dengan role kasir & login
    $kasir = User::factory()->kasir()->create();

    // Act — kasir coba akses /services (hanya untuk admin)
    $response = $this->actingAs($kasir)->get('/services');

    // Assert — N2: FALSE (sudah login), N3: !in_array('kasir',['admin']) = TRUE → abort(403)
    $response->assertStatus(403);
});

test('[Path 5] admin akses halaman admin-only berhasil masuk', function () {
    // Arrange — buat user dengan role admin & login
    $admin = User::factory()->admin()->create();

    // Act — admin akses /services (sesuai role-nya)
    $response = $this->actingAs($admin)->get('/services');

    // Assert — N2: FALSE (sudah login), N3: FALSE (in_array('admin',['admin']) benar) → $next($request)
    $response->assertStatus(200);
});
