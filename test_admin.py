# test_admin.py
# Selenium UI Test — Fitur CRUD Layanan (Admin)
# Berdasarkan State Transition BAB V: Login Admin → CRUD Services

import time
import unittest
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from base_test import BaseTest
from config import BASE_URL, ADMIN_EMAIL, ADMIN_PASSWORD


class TestAdminCRUD(BaseTest):

    def setUp(self):
        super().setUp()
        # Login sebagai admin sebelum setiap test
        self.login(ADMIN_EMAIL, ADMIN_PASSWORD, wait_url="/services")

    def test_01_melihat_daftar_layanan(self):
        """State: Admin → Services — halaman daftar layanan tampil dengan tabel"""
        # Arrange
        self.driver.get(f"{BASE_URL}/services")

        # Assert
        self.assertIn(
            "Data Layanan", self.driver.page_source,
            "Judul 'Data Layanan' harus tampil"
        )
        tabel = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(tabel.is_displayed(), "Tabel layanan harus tampil")

    def test_02_menambah_layanan_baru(self):
        """State: Services → Create Service → Services — tambah layanan berhasil"""
        # Arrange
        self.driver.get(f"{BASE_URL}/services/create")

        # Act
        self.driver.find_element(By.NAME, "name").send_keys("Cukur Selenium Test")
        self.driver.find_element(By.NAME, "price").send_keys("75000")
        self.driver.find_element(
            By.CSS_SELECTOR, "button[type='submit']"
        ).click()

        # Wait & Assert
        self.wait.until(EC.url_contains("/services"))
        self.assertIn(
            "Cukur Selenium Test", self.driver.page_source,
            "Layanan baru harus tampil di tabel setelah berhasil ditambah"
        )

    def test_03_mengedit_layanan(self):
        """State: Services → Edit Service → Services — edit layanan berhasil"""
        # Arrange — buka halaman services
        self.driver.get(f"{BASE_URL}/services")

        # Act — klik tombol Edit pada baris pertama
        edit_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:first-child a.btn-warning"
        )
        edit_btn.click()

        # Ubah nama layanan
        name_input = self.driver.find_element(By.NAME, "name")
        name_input.clear()
        name_input.send_keys("Layanan Edit Selenium")

        price_input = self.driver.find_element(By.NAME, "price")
        price_input.clear()
        price_input.send_keys("50000")

        self.driver.find_element(
            By.CSS_SELECTOR, "button[type='submit']"
        ).click()

        # Wait & Assert
        self.wait.until(EC.url_contains("/services"))
        self.assertIn(
            "Layanan Edit Selenium", self.driver.page_source,
            "Nama layanan yang diedit harus muncul di tabel"
        )

    def test_04_menghapus_layanan(self):
        """State: Services → Delete → Services — hapus layanan berhasil"""
        # Arrange — tambah layanan dulu agar ada yang dihapus
        self.driver.get(f"{BASE_URL}/services/create")
        self.driver.find_element(By.NAME, "name").send_keys("Layanan Hapus Ini")
        self.driver.find_element(By.NAME, "price").send_keys("10000")
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        self.wait.until(EC.url_contains("/services"))

        # Catat jumlah baris sebelum hapus
        rows_before = len(
            self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr")
        )

        # Act — klik Hapus pada baris terakhir
        hapus_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:last-child button.btn-danger"
        )
        # Handle confirm dialog
        self.driver.execute_script(
            "window.confirm = function(){ return true; }"
        )
        hapus_btn.click()
        time.sleep(1)

        # Assert — jumlah baris berkurang 1
        rows_after = len(
            self.driver.find_elements(By.CSS_SELECTOR, "table tbody tr")
        )
        self.assertEqual(
            rows_after, rows_before - 1,
            "Jumlah baris tabel harus berkurang 1 setelah hapus"
        )


if __name__ == "__main__":
    unittest.main()