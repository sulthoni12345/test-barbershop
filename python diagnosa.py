"""
Script diagnosis — jalankan dulu sebelum test:
  python diagnosa.py
Akan print: URL setelah login, source halaman login, dan current_url
"""
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager

BASE_URL = "http://127.0.0.1:8000"

options = webdriver.ChromeOptions()
options.add_argument("--no-sandbox")
options.add_argument("--disable-dev-shm-usage")
options.add_argument("--window-size=1280,720")

driver = webdriver.Chrome(
    service=Service(ChromeDriverManager().install()),
    options=options
)
driver.implicitly_wait(3)

print("=" * 60)
print("STEP 1: Buka halaman login")
driver.get(f"{BASE_URL}/login")
time.sleep(2)

print(f"URL saat ini : {driver.current_url}")
print(f"Judul halaman: {driver.title}")
print()

# Cek semua input yang ada di halaman login
inputs = driver.find_elements(By.TAG_NAME, "input")
print(f"Input elements ditemukan: {len(inputs)}")
for i, inp in enumerate(inputs):
    print(f"  [{i}] type={inp.get_attribute('type')} "
          f"id={inp.get_attribute('id')} "
          f"name={inp.get_attribute('name')} "
          f"class={inp.get_attribute('class')}")

print()
buttons = driver.find_elements(By.TAG_NAME, "button")
print(f"Button elements ditemukan: {len(buttons)}")
for i, btn in enumerate(buttons):
    print(f"  [{i}] type={btn.get_attribute('type')} "
          f"text='{btn.text.strip()}'")

print()
print("=" * 60)
print("STEP 2: Coba login manual")

# Coba isi email — pakai name dulu, lalu id
try:
    em = driver.find_element(By.NAME, "email")
    print("Email field ditemukan via By.NAME='email'")
except:
    try:
        em = driver.find_element(By.ID, "email")
        print("Email field ditemukan via By.ID='email'")
    except:
        print("!!! Email field TIDAK DITEMUKAN via name atau id !!!")
        em = None

try:
    pw = driver.find_element(By.NAME, "password")
    print("Password field ditemukan via By.NAME='password'")
except:
    try:
        pw = driver.find_element(By.ID, "password")
        print("Password field ditemukan via By.ID='password'")
    except:
        print("!!! Password field TIDAK DITEMUKAN !!!")
        pw = None

if em and pw:
    em.send_keys("kasir@barbershop.com")
    pw.send_keys("kasir123")

    try:
        submit = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
        submit.click()
        time.sleep(3)
        print(f"\nSetelah login — URL: {driver.current_url}")
        print(f"Judul halaman  : {driver.title}")
    except Exception as e:
        print(f"Submit gagal: {e}")

print()
print("Tekan Enter untuk tutup browser...")
input()
driver.quit()
