# test_login.py
# Selenium UI Test — Fitur Login
# Berdasarkan State Transition BAB V: Guest → Auth (Admin/Kasir) → Logout

import time
import unittest
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from base_test import BaseTest
from config import BASE_URL, ADMIN_EMAIL, ADMIN_PASSWORD, KASIR_EMAIL, KASIR_PASSWORD


class TestLogin(BaseTest):

    def test_01_halaman_login_tampil(self):
        """State: Guest — halaman login dapat diakses dan form tampil"""
        # Arrange
        self.driver.get(f"{BASE_URL}/login")

        # Assert
        self.assertTrue(
            self.driver.find_element(By.NAME, "email").is_displayed(),
            "Input email harus tampil"
        )
        self.assertTrue(
            self.driver.find_element(By.NAME, "password").is_displayed(),
            "Input password harus tampil"
        )
        self.assertTrue(
            self.driver.find_element(
                By.CSS_SELECTOR, "button[type='submit']"
            ).is_displayed(),
            "Tombol login harus tampil"
        )

    def test_02_admin_berhasil_login_redirect_services(self):
        """State: Guest → Admin — login admin diarahkan ke /services"""
        # Act
        self.login(ADMIN_EMAIL, ADMIN_PASSWORD, wait_url="/services")

        # Assert
        self.assertIn(
            "/services", self.driver.current_url,
            "Admin harus diarahkan ke halaman /services setelah login"
        )

    def test_03_kasir_berhasil_login_redirect_transactions(self):
        """State: Guest → Kasir — login kasir diarahkan ke /transactions"""
        # Act
        self.login(KASIR_EMAIL, KASIR_PASSWORD, wait_url="/transactions")

        # Assert
        self.assertIn(
            "/transactions", self.driver.current_url,
            "Kasir harus diarahkan ke halaman /transactions setelah login"
        )

    def test_04_login_gagal_password_salah(self):
        """State: Guest → Guest — login gagal dengan password salah"""
        # Arrange
        self.driver.get(f"{BASE_URL}/login")

        # Act
        self.driver.find_element(By.NAME, "email").send_keys(ADMIN_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys("passwordSalah999")
        self.driver.find_element(
            By.CSS_SELECTOR, "button[type='submit']"
        ).click()
        time.sleep(1)

        # Assert
        self.assertIn(
            "/login", self.driver.current_url,
            "User harus tetap di halaman /login jika password salah"
        )
        self.assertIn(
            "credentials", self.driver.page_source.lower(),
            "Pesan error kredensial harus tampil"
        )

    def test_05_logout_berhasil(self):
        """State: Admin → Guest — logout berhasil kembali ke halaman login"""
        # Arrange — login dulu
        self.login(ADMIN_EMAIL, ADMIN_PASSWORD, wait_url="/services")

        # Act — logout
        self.logout()
        time.sleep(1)

        # Assert
        self.assertIn(
            "/", self.driver.current_url,
            "Setelah logout harus kembali ke landing page atau /login"
        )


if __name__ == "__main__":
    unittest.main()