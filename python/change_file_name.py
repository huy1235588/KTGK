import os

def rename_files_in_folder(folder_path, new_name_pattern):
    """
    Đổi tên toàn bộ file trong folder_path với mẫu tên mới.
    
    :param folder_path: Đường dẫn tới thư mục.
    :param new_name_pattern: Mẫu tên mới (ví dụ: "file_{index}.txt").
    """
    try:
        files = os.listdir(folder_path)
        files = [f for f in files if os.path.isfile(os.path.join(folder_path, f))]
        
        for index, file_name in enumerate(files, start=1):
            old_path = os.path.join(folder_path, file_name)
            file_extension = os.path.splitext(file_name)[1]
            new_name = new_name_pattern.format(index=index) + file_extension
            new_path = os.path.join(folder_path, new_name)
            
            os.rename(old_path, new_path)
            print(f"Đã đổi: {file_name} -> {new_name}")
        
        print("Hoàn tất đổi tên file.")
    except Exception as e:
        print(f"Đã xảy ra lỗi: {e}")

# Đường dẫn tới thư mục
folder_path = r"images/tao/tao-gala-trung-quoc"

# Mẫu tên mới, bạn có thể thay đổi
new_name_pattern = "hinh_{index}"  # Ví dụ: file_1, file_2, ...

rename_files_in_folder(folder_path, new_name_pattern)
