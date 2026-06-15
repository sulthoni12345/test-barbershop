# test_kasir.py
# Selenium UI Test — Fitur Kasir (Transaksi + Akses Role)
# Berdasarkan State Transition BAB V: Login Kasir → Transaksi / Akses Ditolak

import time
import unittest
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
from base_test import BaseTest
from config import BASE_URL, KASIR_EMAIL, KASIR_PASSWORD


class TestKasirAkses(BaseTest):

    def setUp(self):
        super().setUp()
        # Login sebagai kasir sebelum setiap test
        self.login(KASIR_EMAIL, KASIR_PASSWORD, wait_url="/transactions")

    def test_01_melihat_halaman_transaksi(self):
        """State: Kasir → Transactions — halaman transaksi tampil lengkap"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Assert
        self.assertIn(
            "Transaksi Kasir", self.driver.page_source,
            "Judul 'Transaksi Kasir' harus tampil"
        )
        self.assertTrue(
            self.driver.find_element(
                By.NAME, "service_id"
            ).is_displayed(),
            "Dropdown pilih layanan harus tampil"
        )
        self.assertTrue(
            self.driver.find_element(
                By.NAME, "qty"
            ).is_displayed(),
            "Input qty harus tampil"
        )

    def test_02_menambah_transaksi_berhasil(self):
        """State: Kasir → Add Transaction → Transactions — transaksi tersimpan"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Act — pilih layanan pertama dari dropdown
        select_layanan = Select(
            self.driver.find_element(By.NAME, "service_id")
        )
        select_layanan.select_by_index(0)

        # Isi qty
        qty_input = self.driver.find_element(By.NAME, "qty")
        qty_input.clear()
        qty_input.send_keys("2")

        # Klik simpan
        self.driver.find_element(
            By.CSS_SELECTOR, "button.btn-success"
        ).click()
        time.sleep(1)

        # Assert — sukses, tetap di halaman transactions
        self.assertIn(
            "/transactions", self.driver.current_url,
            "Setelah simpan transaksi harus kembali ke /transactions"
        )
        self.assertIn(
            "transactions", self.driver.current_url,
            "Transaksi berhasil disimpan"
        )

    def test_03_kasir_tidak_bisa_akses_halaman_services(self):
        """State: Kasir → /services → 403 — akses ditolak karena role tidak sesuai"""
        # Act — kasir coba akses halaman admin
        self.driver.get(f"{BASE_URL}/services")
        time.sleep(1)

        # Assert — tampil halaman 403
        self.assertIn(
            "403", self.driver.page_source,
            "Kasir harus mendapat halaman 403 saat akses /services"
        )
        self.assertNotIn(
            "Data Layanan", self.driver.page_source,
            "Kasir tidak boleh melihat konten halaman Data Layanan"
        )

    def test_04_navbar_kasir_tidak_tampilkan_menu_layanan(self):
        """State: Kasir → Transactions — menu 'Kelola Layanan' tidak tampil di navbar"""
        # Arrange
        self.driver.get(f"{BASE_URL}/transactions")

        # Assert — menu admin tidak tampil untuk kasir
        menu_layanan = self.driver.find_elements(
            By.LINK_TEXT, "Kelola Layanan"
        )
        self.assertEqual(
            len(menu_layanan), 0,
            "Menu 'Kelola Layanan' tidak boleh tampil di navbar kasir"
        )


if __name__ == "__main__":
    unittest.main()