<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;

// ============================================================
// FEATURE TEST — Tambah Transaksi (Kasir)
// Berdasarkan Equivalence Partitioning BAB II (7 kelas EP)
// ============================================================

// --- Kelas Valid ---

test('[EP-S1] kasir pilih layanan valid transaksi berhasil disimpan', function () {
    $kasir   = User::factory()->kasir()->create();
    $service = Service::factory()->create(['price' => 20000]);

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => $service->id,
        'qty'        => 2,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('transactions', [
        'service_id' => $service->id,
        'qty'        => 2,
        'total'      => 40000,
    ]);
});

test('[EP-Q1] kasir input qty valid (min 1) total dihitung otomatis benar', function () {
    $kasir   = User::factory()->kasir()->create();
    $service = Service::factory()->create(['price' => 15000]);

    $this->actingAs($kasir)->post('/transactions', [
        'service_id' => $service->id,
        'qty'        => 3,
    ]);

    $this->assertDatabaseHas('transactions', [
        'service_id' => $service->id,
        'qty'        => 3,
        'total'      => 45000,
    ]);
});

// --- Kelas Invalid ---

test('[EP-S2] kasir tidak memilih layanan mendapat error validasi', function () {
    $kasir = User::factory()->kasir()->create();

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => '',
        'qty'        => 1,
    ]);

    $response->assertSessionHasErrors(['service_id']);
});

test('[EP-S3] kasir input service_id yang tidak ada di database mendapat error validasi', function () {
    $kasir = User::factory()->kasir()->create();

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => 9999,
        'qty'        => 1,
    ]);

    $response->assertSessionHasErrors(['service_id']);
});

test('[EP-Q2] kasir input qty kosong mendapat error validasi', function () {
    $kasir   = User::factory()->kasir()->create();
    $service = Service::factory()->create();

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => $service->id,
        'qty'        => '',
    ]);

    $response->assertSessionHasErrors(['qty']);
});

test('[EP-Q3] kasir input qty nol mendapat error validasi min:1', function () {
    $kasir   = User::factory()->kasir()->create();
    $service = Service::factory()->create();

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => $service->id,
        'qty'        => 0,
    ]);

    $response->assertSessionHasErrors(['qty']);
});

test('[EP-Q4] kasir input qty bukan angka mendapat error validasi', function () {
    $kasir   = User::factory()->kasir()->create();
    $service = Service::factory()->create();

    $response = $this->actingAs($kasir)->post('/transactions', [
        'service_id' => $service->id,
        'qty'        => 'dua',
    ]);

    $response->assertSessionHasErrors(['qty']);
});
