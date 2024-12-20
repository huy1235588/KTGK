from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
import os
import requests
import time

# Thiết lập Selenium
options = Options()
options.add_argument('--headless')  # Chạy ở chế độ ẩn
options.add_argument('--disable-gpu')
options.add_argument('--no-sandbox')
service = Service('path/to/chromedriver')  # Thay 'path/to/chromedriver' bằng đường dẫn thực tế

driver = webdriver.Chrome(service=service, options=options)

# Danh sách URL hình ảnh
image_urls = [
    "link1",  # Thay bằng các URL thực tế
    "link2",
    "link3",
]

# Thư mục lưu hình ảnh
output_folder = "downloaded_images"
os.makedirs(output_folder, exist_ok=True)

def download_image(url, folder, index):
    try:
        response = requests.get(url, stream=True)
        if response.status_code == 200:
            file_path = os.path.join(folder, f"image_{index}.jpg")
            with open(file_path, 'wb') as file:
                for chunk in response.iter_content(1024):
                    file.write(chunk)
            print(f"Downloaded: {file_path}")
        else:
            print(f"Failed to download {url}: Status code {response.status_code}")
    except Exception as e:
        print(f"Error downloading {url}: {e}")

# Lặp qua các URL hình ảnh và tải xuống
for idx, img_url in enumerate(image_urls, start=1):
    driver.get(img_url)  # Mở URL hình ảnh bằng Selenium
    time.sleep(2)  # Chờ tải trang
    try:
        img_element = driver.find_element(By.TAG_NAME, 'img')  # Tìm thẻ <img>
        img_src = img_element.get_attribute('src')  # Lấy URL của hình ảnh
        download_image(img_src, output_folder, idx)
    except Exception as e:
        print(f"Error processing {img_url}: {e}")

# Đóng trình duyệt
driver.quit()
