<?php

use App\Models\User;
use App\Models\Service;



test('[EP-N1] admin tambah layanan dengan nama valid berhasil disimpan', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/services', [
        'name'  => 'Cukur Rambut',
        'price' => 35000,
    ]);

    $response->assertRedirect(route('services.index'));
    $this->assertDatabaseHas('services', [
        'name'  => 'Cukur Rambut',
        'price' => 35000,
    ]);
});

test('[EP-H1] admin tambah layanan dengan harga integer valid berhasil disimpan', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/services', [
        'name'  => 'Keramas',
        'price' => 25000,
    ]);

    $response->assertRedirect(route('services.index'));
    $this->assertDatabaseHas('services', ['price' => 25000]);
});

// --- Kelas Invalid ---

test('[EP-N2] admin tambah layanan dengan nama kosong mendapat error validasi', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/services', [
        'name'  => '',
        'price' => 35000,
    ]);

    $response->assertSessionHasErrors(['name']);
    $this->assertDatabaseMissing('services', ['price' => 35000]);
});

test('[EP-H2] admin tambah layanan dengan harga kosong mendapat error validasi', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/services', [
        'name'  => 'Cukur Jenggot',
        'price' => '',
    ]);

    $response->assertSessionHasErrors(['price']);
    $this->assertDatabaseMissing('services', ['name' => 'Cukur Jenggot']);
});

test('[EP-H3] admin tambah layanan dengan harga bukan angka mendapat error validasi', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/services', [
        'name'  => 'Blow Dry',
        'price' => 'tiga puluh ribu',
    ]);

    $response->assertSessionHasErrors(['price']);
    $this->assertDatabaseMissing('services', ['name' => 'Blow Dry']);
});
