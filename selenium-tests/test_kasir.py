# test_kasir.py
# File: selenium-tests/test_kasir.py
# TEST KASIR — Transaksi (4 test)
# Kelompok Der Panzer | Pengujian dan Implementasi Sistem

import unittest
from datetime import date
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from base_test import BaseTest
from config import BASE_URL, KASIR_EMAIL, KASIR_PASSWORD


class TestKasirAkses(BaseTest):

    def setUp(self):
        """Login sebagai kasir sebelum setiap test"""
        super().setUp()
        self.login(KASIR_EMAIL, KASIR_PASSWORD)
        self.wait.until(EC.url_contains("/transactions"))

    # ---------------------------------------------------------------
    # test_01 — State S3: Kasir Dashboard (/transactions)
    # Verifikasi: 3 kartu summary + tabel riwayat tampil
    # ---------------------------------------------------------------
    def test_01_melihat_dashboard_dan_riwayat_transaksi(self):
        """State S3: Kasir Dashboard — kartu summary dan tabel riwayat tampil"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Assert — 3 kartu summary (Total Hari Ini, Total Keseluruhan, Jumlah Transaksi)
        cards = self.driver.find_elements(By.CSS_SELECTOR, ".card.shadow-sm.border-0")
        self.assertGreaterEqual(len(cards), 3)

        # Assert — tabel riwayat transaksi tampil
        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

        # Assert — tabel memiliki kolom yang benar
        self.assertIn("Layanan", self.driver.page_source)
        self.assertIn("Total", self.driver.page_source)
        self.assertIn("Cetak Struk", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_02 — State S3: Filter Riwayat Transaksi
    # Transisi: Isi filter tanggal → klik Filter → hasil berubah sesuai range
    # ---------------------------------------------------------------
    def test_02_filter_riwayat_transaksi(self):
        """State S3: Filter tanggal — tabel menampilkan transaksi sesuai range"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Ambil tanggal hari ini dalam format yyyy-mm-dd untuk input[type=date]
        today = date.today().strftime("%Y-%m-%d")

        # Act — isi filter "Dari Tanggal" dan "Sampai Tanggal" dengan hari ini
        dari_input = self.driver.find_element(By.NAME, "dari")
        dari_input.send_keys(today)

        sampai_input = self.driver.find_element(By.NAME, "sampai")
        sampai_input.send_keys(today)

        # Klik tombol Filter (btn-primary di form GET filter)
        self.driver.find_element(
            By.CSS_SELECTOR, "form[method='GET'] button[type='submit']"
        ).click()

        # Assert — URL mengandung parameter filter dan halaman tetap di /transactions
        self.wait.until(EC.url_contains("/transactions"))
        self.assertIn("dari=", self.driver.current_url)
        self.assertIn("sampai=", self.driver.current_url)

        # Assert — tabel tetap tampil setelah filter
        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

    # ---------------------------------------------------------------
    # test_03 — State S3 → S6: Kasir akses /services → 403 Forbidden
    # Transisi: Kasir paksa akses halaman admin via URL langsung
    # ---------------------------------------------------------------
    def test_03_kasir_akses_services_ditolak_403(self):
        """State S6: Kasir akses /services langsung via URL → 403 Forbidden"""
        # Act — kasir paksa akses halaman kelola layanan milik admin
        self.driver.get(f"{BASE_URL}/services")

        # Assert — halaman 403 Forbidden tampil
        self.assertIn("403", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_04 — State S3 → S8: Klik Cetak Struk → halaman struk tampil
    # Transisi: Klik tombol Cetak Struk baris pertama → URL /struk + konten tampil
    # ---------------------------------------------------------------
    def test_04_melihat_struk_transaksi(self):
        """State S8: Halaman Struk — detail transaksi tampil setelah klik Cetak Struk"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Tunggu tabel transaksi muncul
        self.wait.until(EC.presence_of_element_located(
            By.CSS_SELECTOR, "table tbody tr"
        ))

        # Act — klik tombol Cetak Struk pada baris pertama tabel
        # Selector: a.btn-outline-primary di baris pertama tbody
        struk_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:first-child a.btn-outline-primary"
        )
        struk_link = struk_btn.get_attribute("href")

        # Buka URL struk langsung (tombol punya target="_blank", buka di tab sama)
        self.driver.get(struk_link)

        # Assert — URL mengandung /struk dan konten struk tampil
        self.assertIn("struk", self.driver.current_url)
        self.assertIn("Total", self.driver.page_source)


if __name__ == "__main__":
    unittest.main()
