# test_admin.py
# File: selenium-tests/test_admin.py
# TEST ADMIN — CRUD Layanan (4 test)
# Kelompok Der Panzer | Pengujian dan Implementasi Sistem

import unittest
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from base_test import BaseTest
from config import BASE_URL, ADMIN_EMAIL, ADMIN_PASSWORD


class TestAdminCRUD(BaseTest):

    def setUp(self):
        """Login sebagai admin sebelum setiap test"""
        super().setUp()
        self.login(ADMIN_EMAIL, ADMIN_PASSWORD)

    # ---------------------------------------------------------------
    # test_01 — State S2: Admin Dashboard — tabel daftar layanan tampil
    # ---------------------------------------------------------------
    def test_01_melihat_daftar_layanan(self):
        """State S2: Admin Dashboard — tabel daftar layanan tampil"""
        # Arrange
        self.driver.get(f"{BASE_URL}/services")

        # Assert — tabel layanan tersedia di halaman
        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

    # ---------------------------------------------------------------
    # test_02 — State S4: Form Tambah Layanan → submit valid → S2
    # ---------------------------------------------------------------
    def test_02_menambah_layanan_baru(self):
        """State S4: Form Tambah Layanan — submit valid → data tersimpan di tabel"""
        # Arrange
        self.driver.get(f"{BASE_URL}/services/create")

        # Act — isi form
        self.driver.find_element(By.NAME, "name").send_keys("Cukur Selenium Test")
        self.driver.find_element(By.NAME, "price").send_keys("75000")

        # Tombol Simpan di create.blade.php: <button class="btn btn-primary">
        self.driver.find_element(
            By.CSS_SELECTOR, ".card-body button.btn-primary"
        ).click()

        # Assert — redirect ke /services dan nama layanan muncul di tabel
        self.wait.until(EC.url_contains("/services"))
        self.assertIn("Cukur Selenium Test", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_03 — State S5: Form Edit Layanan → submit valid → S2
    # FIX: edit.blade.php pakai btn-warning bukan btn-primary
    # ---------------------------------------------------------------
    def test_03_mengedit_layanan(self):
        """State S5: Form Edit Layanan — submit valid → data terupdate di tabel"""
        # Arrange — buka halaman services
        self.driver.get(f"{BASE_URL}/services")

        # Act — klik tombol Edit (btn-warning) pada baris pertama tabel
        edit_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:first-child a.btn-warning"
        )
        edit_btn.click()

        # Ubah nama dan harga layanan
        name_input = self.driver.find_element(By.NAME, "name")
        name_input.clear()
        name_input.send_keys("Layanan Edit Selenium")

        price_input = self.driver.find_element(By.NAME, "price")
        price_input.clear()
        price_input.send_keys("50000")

        # FIX: tombol Update di edit.blade.php pakai class btn-warning
        # <button class="btn btn-warning">Update</button>
        self.driver.find_element(
            By.CSS_SELECTOR, ".card-body button.btn-warning"
        ).click()

        # Assert — redirect ke /services dan nama baru muncul
        self.wait.until(EC.url_contains("/services"))
        self.assertIn("Layanan Edit Selenium", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_04 — State S2: Hapus layanan → data hilang dari tabel
    # FIX: tombol hapus memunculkan JS confirm() → harus di-accept dulu
    # ---------------------------------------------------------------
    def test_04_menghapus_layanan(self):
        """State S2: Hapus layanan — data hilang dari tabel setelah dihapus"""
        # Arrange — tambah layanan dummy dulu agar ada yang dihapus
        self.driver.get(f"{BASE_URL}/services/create")
        self.driver.find_element(By.NAME, "name").send_keys("Layanan Hapus Ini")
        self.driver.find_element(By.NAME, "price").send_keys("10000")
        self.driver.find_element(
            By.CSS_SELECTOR, ".card-body button.btn-primary"
        ).click()
        self.wait.until(EC.url_contains("/services"))

        # Catat jumlah baris sebelum hapus
        rows_before = len(self.driver.find_elements(
            By.CSS_SELECTOR, "table tbody tr"
        ))

        # Act — klik tombol Hapus (btn-danger) pada baris terakhir tabel
        delete_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:last-child button.btn-danger"
        )
        delete_btn.click()

        # FIX: tombol hapus memunculkan confirm() "Hapus layanan?"
        # harus accept dialog JS sebelum Selenium bisa lanjut
        self.driver.switch_to.alert.accept()

        # Assert — tunggu redirect dan jumlah baris berkurang 1
        self.wait.until(EC.url_contains("/services"))
        rows_after = len(self.driver.find_elements(
            By.CSS_SELECTOR, "table tbody tr"
        ))
        self.assertEqual(rows_after, rows_before - 1)


if __name__ == "__main__":
    unittest.main()
