# base_test.py

import os
os.environ['WDM_LOCAL'] = '1'

import time
import unittest
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
from config import BASE_URL


class BaseTest(unittest.TestCase):

    def setUp(self):
        options = webdriver.ChromeOptions()
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--disable-gpu")
        options.add_argument("--disable-extensions")
        options.add_argument("--disable-infobars")
        options.add_argument("--disable-popup-blocking")
        options.add_argument("--start-maximized")
        options.add_argument("--remote-debugging-port=9222")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)

        self.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=options
        )
        self.driver.implicitly_wait(10)
        self.wait = WebDriverWait(self.driver, 15)
        time.sleep(1)  # cukup 1 detik, Chrome sudah stabil

    def tearDown(self):
        try:
            self.driver.quit()
        except Exception:
            pass

    def login(self, email, password, wait_url=None):
        self.driver.get(f"{BASE_URL}/login")

        # Tunggu form login benar-benar siap, bukan sleep buta
        self.wait.until(
            EC.presence_of_element_located((By.NAME, "email"))
        )

        self.driver.find_element(By.NAME, "email").clear()
        self.driver.find_element(By.NAME, "email").send_keys(email)
        self.driver.find_element(By.NAME, "password").clear()
        self.driver.find_element(By.NAME, "password").send_keys(password)
        self.driver.find_element(
            By.CSS_SELECTOR, "button[type='submit']"
        ).click()

        # WAJIB tunggu redirect selesai sebelum lanjut ke test
        # default wait_url jika tidak diisi pemanggil
        target_url = wait_url if wait_url else "/"
        self.wait.until(EC.url_contains(target_url))

    def logout(self):
        try:
            self.driver.find_element(
                By.CSS_SELECTOR, ".dropdown-toggle"
            ).click()
            self.wait.until(EC.element_to_be_clickable(
                (By.CSS_SELECTOR, ".dropdown-menu .dropdown-item")
            )).click()
        except Exception:
            pass
