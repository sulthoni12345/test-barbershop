# test_kasir.py
# File: selenium-tests/test_kasir.py
# TEST KASIR — Transaksi & Kontrol Akses (4 test)
# Kelompok Der Panzer | Pengujian dan Implementasi Sistem

import unittest
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from base_test import BaseTest
from config import (
    BASE_URL,
    KASIR_EMAIL,
    KASIR_PASSWORD
)


class TestKasirAkses(BaseTest):

    def setUp(self):
        """
        Login sebagai kasir sebelum setiap test
        Menggunakan helper login dari BaseTest
        """
        super().setUp()

        # Login menggunakan method yang sudah terbukti berjalan
        self.login(
            KASIR_EMAIL,
            KASIR_PASSWORD
        )

        # Pastikan login berhasil dan redirect ke dashboard kasir
        self.wait.until(
            EC.url_contains("/transactions")
        )

    # ---------------------------------------------------------------
    # test_01 — State: S3 Kasir Dashboard (/transactions)
    # Transisi: Login kasir valid → redirect /transactions → tabel tampil
    # ---------------------------------------------------------------
    def test_01_melihat_daftar_transaksi(self):
        """State S3: Kasir Dashboard — tabel daftar transaksi tampil"""

        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Assert
        table = self.driver.find_element(
            By.CSS_SELECTOR,
            "table"
        )

        self.assertTrue(table.is_displayed())

    # ---------------------------------------------------------------
    # test_02 — State: S3 Kasir Dashboard → S7 Form Transaksi → S3
    # Transisi: Klik Tambah Transaksi → isi form valid
    # → submit → kembali ke /transactions
    # ---------------------------------------------------------------
    def test_02_menambah_transaksi_baru(self):
        """State S7: Form Tambah Transaksi — submit valid"""

        # Arrange
        self.driver.get(
            f"{BASE_URL}/transactions/create"
        )

        # Pilih layanan pertama
        select_service = self.driver.find_element(
            By.NAME,
            "service_id"
        )

        options = select_service.find_elements(
            By.TAG_NAME,
            "option"
        )

        for option in options:
            if option.get_attribute("value"):
                option.click()
                break

        # Isi qty
        self.driver.find_element(
            By.NAME,
            "qty"
        ).send_keys("2")

        # Submit form
        self.driver.find_element(
            By.CSS_SELECTOR,
            ".card-body button.btn-primary"
        ).click()

        # Assert redirect berhasil
        self.wait.until(
            EC.url_contains("/transactions")
        )

        self.assertIn(
            "/transactions",
            self.driver.current_url
        )

    # ---------------------------------------------------------------
    # test_03 — State: S3 Kasir Dashboard → S6 (403 Forbidden)
    # Transisi: Kasir akses /services → ditolak
    # ---------------------------------------------------------------
    def test_03_kasir_akses_services_ditolak_403(self):
        """State S6: Kasir tidak boleh akses halaman admin"""

        self.driver.get(
            f"{BASE_URL}/services"
        )

        self.assertIn(
            "403",
            self.driver.page_source
        )

    # ---------------------------------------------------------------
    # test_04 — State: S3 Kasir Dashboard → S8 Halaman Struk
    # Transisi: Klik Cetak Struk → halaman struk tampil
    # ---------------------------------------------------------------
    def test_04_melihat_struk_transaksi(self):
        """State S8: Halaman Struk transaksi"""

        self.driver.get(
            f"{BASE_URL}/transactions"
        )

        struk_btn = self.driver.find_element(
            By.CSS_SELECTOR,
            "table tbody tr:first-child a.btn-info"
        )

        struk_btn.click()

        self.wait.until(
            EC.url_contains("struk")
        )

        self.assertIn(
            "struk",
            self.driver.current_url.lower()
        )

        self.assertIn(
            "Total",
            self.driver.page_source
        )


if __name__ == "__main__":
    unittest.main()
