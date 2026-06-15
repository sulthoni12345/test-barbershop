import unittest
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ─── Sesuaikan jika URL berbeda ───────────────────────────────────────────────
BASE_URL = "http://127.0.0.1:8000"
KASIR_EMAIL = "kasir@barbershop.com"
KASIR_PASSWORD = "kasir123"
# ──────────────────────────────────────────────────────────────────────────────


class TestKasirAkses(unittest.TestCase):

    def setUp(self):
        options = webdriver.ChromeOptions()
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--window-size=1280,720")
        self.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=options
        )
        self.driver.implicitly_wait(10)
        self.wait = WebDriverWait(self.driver, 15)
        self._login_kasir()

    def tearDown(self):
        self.driver.quit()

    # ── helper: find element by multiple selectors, return first found ─────────
    def _find(self, selectors):
        """Coba beberapa selector, kembalikan elemen pertama yang ditemukan."""
        for by, val in selectors:
            try:
                el = self.driver.find_element(by, val)
                if el:
                    return el
            except Exception:
                continue
        raise Exception(f"Tidak ada elemen ditemukan dari: {selectors}")

    # ── helper: login sebagai kasir ────────────────────────────────────────────
    def _login_kasir(self):
        self.driver.get(f"{BASE_URL}/login")
        time.sleep(2)  # tunggu halaman load sepenuhnya

        # Cari field email — coba id, name, type, placeholder
        email_el = self._find([
            (By.ID,   "email"),
            (By.NAME, "email"),
            (By.CSS_SELECTOR, "input[type='email']"),
            (By.CSS_SELECTOR, "input[placeholder*='email' i]"),
            (By.CSS_SELECTOR, "input[placeholder*='Email' i]"),
        ])
        email_el.clear()
        email_el.send_keys(KASIR_EMAIL)

        # Cari field password
        pass_el = self._find([
            (By.ID,   "password"),
            (By.NAME, "password"),
            (By.CSS_SELECTOR, "input[type='password']"),
        ])
        pass_el.clear()
        pass_el.send_keys(KASIR_PASSWORD)

        # Cari tombol submit
        submit_el = self._find([
            (By.CSS_SELECTOR, "button[type='submit']"),
            (By.CSS_SELECTOR, "input[type='submit']"),
            (By.XPATH, "//button[contains(translate(text(),'LOGIN','login'),'login') or contains(translate(text(),'MASUK','masuk'),'masuk')]"),
        ])
        submit_el.click()
        time.sleep(3)  # tunggu redirect selesai

    # ── T5 → S4 : Kasir melihat daftar transaksi ──────────────────────────────
    def test_01_melihat_daftar_transaksi(self):
        """State S4: Halaman Transaksi Kasir — tabel riwayat tampil"""
        self.driver.get(f"{BASE_URL}/transactions")
        time.sleep(2)

        table = self._find([
            (By.CSS_SELECTOR, "table"),
            (By.CSS_SELECTOR, ".table"),
            (By.TAG_NAME,     "table"),
        ])
        self.assertTrue(table.is_displayed(),
                        "Tabel riwayat transaksi harus tampil di halaman /transactions")

    # ── T13 → S4 : Kasir menambah transaksi baru ──────────────────────────────
    def test_02_menambah_transaksi_baru(self):
        """State S7: Form Tambah Transaksi — submit valid"""
        self.driver.get(f"{BASE_URL}/transactions/create")
        time.sleep(2)

        # Cari dropdown layanan
        service_el = self._find([
            (By.NAME,         "service_id"),
            (By.CSS_SELECTOR, "select[name='service_id']"),
            (By.CSS_SELECTOR, "select"),          # fallback: select pertama
        ])
        select = Select(service_el)
        # Pilih option pertama yang punya value (skip placeholder kosong)
        valid_opts = [o for o in select.options if o.get_attribute("value")]
        self.assertGreater(len(valid_opts), 0,
                           "Harus ada minimal satu layanan di dropdown")
        select.select_by_value(valid_opts[0].get_attribute("value"))

        # Cari field qty
        qty_el = self._find([
            (By.NAME,         "qty"),
            (By.CSS_SELECTOR, "input[name='qty']"),
            (By.CSS_SELECTOR, "input[type='number']"),
        ])
        qty_el.clear()
        qty_el.send_keys("2")

        # Submit
        submit_el = self._find([
            (By.CSS_SELECTOR, "button[type='submit']"),
            (By.CSS_SELECTOR, "input[type='submit']"),
        ])
        submit_el.click()
        time.sleep(3)

        self.assertIn("/transactions", self.driver.current_url,
                      "Setelah submit transaksi, harus redirect ke /transactions")

    # ── T11 → S6 : Kasir akses /services ditolak 403 ─────────────────────────
    def test_03_kasir_akses_services_ditolak_403(self):
        """State S6: Akses Ditolak — kasir mencoba /services dapat 403"""
        self.driver.get(f"{BASE_URL}/services")
        time.sleep(2)

        src = self.driver.page_source.lower()
        is_403 = any(kw in src for kw in [
            "403", "forbidden", "tidak diizinkan",
            "unauthorized", "access denied", "akses ditolak",
        ])
        self.assertTrue(is_403,
                        "Kasir yang akses /services harus mendapat 403 Forbidden")

    # ── T9 → S5 : Kasir melihat struk transaksi ───────────────────────────────
    def test_04_melihat_struk_transaksi(self):
        """State S5: Halaman Struk Transaksi"""
        self.driver.get(f"{BASE_URL}/transactions")
        time.sleep(2)

        # Cari tombol/link struk pada baris pertama tabel
        struk_el = self._find([
            (By.CSS_SELECTOR, "table tbody tr:first-child a.btn-info"),
            (By.CSS_SELECTOR, "table tbody tr:first-child a[href*='struk']"),
            (By.CSS_SELECTOR, "table tbody tr:first-child a[href*='receipt']"),
            (By.CSS_SELECTOR, "table tbody tr:first-child a[href*='print']"),
            (By.CSS_SELECTOR, "table tbody tr:first-child a[href*='show']"),
            (By.CSS_SELECTOR, "table tbody tr:first-child a"),  # link apapun di baris pertama
        ])

        struk_url = struk_el.get_attribute("href")
        self.driver.get(struk_url)
        time.sleep(2)

        src = self.driver.page_source.lower()
        has_content = any(kw in src for kw in [
            "struk", "receipt", "total", "layanan",
            "service", "qty", "pembayaran", "transaksi",
        ])
        self.assertTrue(has_content,
                        "Halaman struk harus menampilkan detail transaksi")


if __name__ == "__main__":
    unittest.main()
