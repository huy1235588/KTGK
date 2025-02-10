import os
import requests

def download_images(items):
    for item in items:
        # Đọc danh sách URL từ file
        file_path = f"python/upload/{item}/screenshots/{item}.txt"
        output_folder = f"python/upload/{item}/screenshots"

        # Tạo thư mục nếu chưa có
        if not os.path.exists(output_folder):
            os.makedirs(output_folder)

        # Đọc file và tải ảnh
        with open(file_path, "r") as file:
            urls = file.readlines()

        for index, url in enumerate(urls):
            # Xóa dấu phẩy, khoảng trắng ở đầu và cuối dòng
            url = url.strip().replace(",", "")
            if not url:
                continue

            try:
                response = requests.get(url, stream=True)
                if response.status_code == 200:
                    image_extension = url.split(".")[-1].split("?")[
                        0
                    ]  # Lấy phần mở rộng của file
                    image_name = f"image_{index}.{image_extension}"
                    image_path = os.path.join(output_folder, image_name)

                    with open(image_path, "wb") as img_file:
                        for chunk in response.iter_content(1024):
                            img_file.write(chunk)
                    print(f"Tải thành công: {image_name}")
                else:
                    print(f"Lỗi {response.status_code}: Không thể tải {url}")

            except Exception as e:
                print(f"Lỗi khi tải {url}: {e}")

        print(f"Hoàn tất tải ảnh cho {item}")

items = [
    'genshin',
    'honkai',
    'zenless'
]

if __name__ == '__main__':
    download_images(items)