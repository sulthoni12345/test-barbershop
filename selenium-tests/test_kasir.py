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
        self.driver.get(f"{BASE_URL}/transactions")

        cards = self.driver.find_elements(By.CSS_SELECTOR, ".card.shadow-sm.border-0")
        self.assertGreaterEqual(len(cards), 3)

        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

        self.assertIn("Layanan", self.driver.page_source)
        self.assertIn("Total", self.driver.page_source)
        self.assertIn("Cetak Struk", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_02 — State S3: Filter Riwayat Transaksi
    # FIX: submit form langsung via JS agar param dari= & sampai= masuk ke URL
    #      (JS set value tidak trigger event browser, jadi form.submit() lebih andal)
    # ---------------------------------------------------------------
    def test_02_filter_riwayat_transaksi(self):
        """State S3: Filter tanggal — URL mengandung param dari= dan sampai="""
        self.driver.get(f"{BASE_URL}/transactions")
        today = date.today().strftime("%Y-%m-%d")

        # Set value via JS
        dari_input = self.driver.find_element(By.NAME, "dari")
        self.driver.execute_script("arguments[0].value = arguments[1];", dari_input, today)

        sampai_input = self.driver.find_element(By.NAME, "sampai")
        self.driver.execute_script("arguments[0].value = arguments[1];", sampai_input, today)

        # FIX: submit form langsung via JS — lebih andal daripada .click() tombol
        #      karena JS set value tidak selalu trigger event yang dibutuhkan browser
        filter_form = self.driver.find_element(By.CSS_SELECTOR, "form[method='GET']")
        self.driver.execute_script("arguments[0].submit();", filter_form)

        # Assert — URL mengandung parameter filter
        self.wait.until(EC.url_contains("dari="))
        self.assertIn("dari=", self.driver.current_url)
        self.assertIn("sampai=", self.driver.current_url)

        # Assert — tabel tetap tampil setelah filter
        table = self.driver.find_element(By.CSS_SELECTOR, "table")
        self.assertTrue(table.is_displayed())

    # ---------------------------------------------------------------
    # test_03 — State S3 → S6: Kasir akses /services → 403 Forbidden
    # ---------------------------------------------------------------
    def test_03_kasir_akses_services_ditolak_403(self):
        """State S6: Kasir akses /services langsung via URL → 403 Forbidden"""
        self.driver.get(f"{BASE_URL}/services")
        self.assertIn("403", self.driver.page_source)

    # ---------------------------------------------------------------
    # test_04 — State S3 → S8: Klik Cetak Struk → halaman struk tampil
    # FIX: assert "TOTAL" bukan "Total" — halaman struk pakai huruf kapital semua
    # ---------------------------------------------------------------
    def test_04_melihat_struk_transaksi(self):
        """State S8: Halaman Struk — detail transaksi tampil setelah klik Cetak Struk"""
        self.driver.get(f"{BASE_URL}/transactions")

        self.wait.until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "table tbody tr"))
        )

        struk_btn = self.driver.find_element(
            By.CSS_SELECTOR, "table tbody tr:first-child a.btn-outline-primary"
        )
        struk_link = struk_btn.get_attribute("href")
        self.driver.get(struk_link)

        self.assertIn("struk", self.driver.current_url)
        # FIX: halaman struk menampilkan "TOTAL" kapital, bukan "Total"
        self.assertIn("TOTAL", self.driver.page_source)


if __name__ == "__main__":
    unittest.main()
