# test_admin.py
# File: selenium-tests/test_admin.py
# TEST ADMIN — CRUD Layanan (4 test)
# Kelompok Der Panzer | Pengujian dan Implementasi Sistem

import time
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
        self.driver.get(f"{BASE_URL}/services")

        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

    # ---------------------------------------------------------------
    # test_02 — State S4: Form Tambah Layanan → submit valid → S2
    # FIX: submit form via JavaScript langsung (bypass tombol Simpan)
    #      karena tombol tidak punya type="submit" dan .click() tidak
    #      selalu trigger submit jika elemen tidak fully visible.
    #      JS form.submit() langsung kirim data DOM ke server.
    # ---------------------------------------------------------------
    def test_02_menambah_layanan_baru(self):
        """State S4: Form Tambah Layanan — submit valid → data tersimpan di tabel"""
        # Arrange — nama unik per run agar tidak bentrok data lama di DB
        self.layanan_name = f"Cukur Selenium {int(time.time())}"

        self.driver.get(f"{BASE_URL}/services/create")

        # Tunggu form muncul sebelum diisi
        self.wait.until(
            EC.presence_of_element_located((By.NAME, "name"))
        )

        # Act — isi form
        self.driver.find_element(By.NAME, "name").send_keys(self.layanan_name)
        self.driver.find_element(By.NAME, "price").send_keys("75000")

        # FIX: submit form via JS langsung — lebih andal daripada .click() tombol
        # karena tombol tidak punya type="submit" dan bisa tidak ter-trigger
        form = self.driver.find_element(
            By.CSS_SELECTOR, ".card-body form"
        )
        self.driver.execute_script("arguments[0].submit();", form)

        # Assert — redirect ke /services dan nama layanan muncul di tabel
        self.wait.until(EC.url_contains("/services"))
        self.assertIn(self.layanan_name, self.driver.page_source)

    # ---------------------------------------------------------------
    # test_03 — State S5: Form Edit Layanan → submit valid → S2
    # ---------------------------------------------------------------
    def test_03_mengedit_layanan(self):
        """State S5: Form Edit Layanan — submit valid → data terupdate di tabel"""
        self.driver.get(f"{BASE_URL}/services")

        edit_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:first-child a.btn-warning"
        )
        edit_btn.click()

        name_input = self.driver.find_element(By.NAME, "name")
        name_input.clear()
        name_input.send_keys("Layanan Edit Selenium")

        price_input = self.driver.find_element(By.NAME, "price")
        price_input.clear()
        price_input.send_keys("50000")

        self.driver.find_element(
            By.CSS_SELECTOR, ".card-body button.btn-warning"
        ).click()

        self.wait.until(EC.url_contains("/services"))
        self.assertIn("Layanan Edit Selenium", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_04 — State S2: Hapus layanan → data hilang dari tabel
    # ---------------------------------------------------------------
    def test_04_menghapus_layanan(self):
        """State S2: Hapus layanan — data hilang dari tabel setelah dihapus"""
        # Arrange — tambah layanan dummy dulu agar ada yang dihapus
        self.driver.get(f"{BASE_URL}/services/create")

        self.wait.until(
            EC.presence_of_element_located((By.NAME, "name"))
        )

        self.driver.find_element(By.NAME, "name").send_keys("Layanan Hapus Ini")
        self.driver.find_element(By.NAME, "price").send_keys("10000")

        form = self.driver.find_element(By.CSS_SELECTOR, ".card-body form")
        self.driver.execute_script("arguments[0].submit();", form)

        self.wait.until(EC.url_contains("/services"))

        rows_before = len(self.driver.find_elements(
            By.CSS_SELECTOR, "table tbody tr"
        ))

        delete_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:last-child button.btn-danger"
        )
        self.driver.execute_script("arguments[0].scrollIntoView(true);", delete_btn)
        self.driver.execute_script("arguments[0].click();", delete_btn)
        self.driver.switch_to.alert.accept()

        self.wait.until(EC.url_contains("/services"))
        rows_after = len(self.driver.find_elements(
            By.CSS_SELECTOR, "table tbody tr"
        ))
        self.assertEqual(rows_after, rows_before - 1)


if __name__ == "__main__":
    unittest.main()
