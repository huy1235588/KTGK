/*******************************************
* 
*          Valid Form
* 
*******************************************/
const validateForm = async () => {
    const form = document.getElementById("profileForm");

    // Lấy dữ liệu form
    const formData = new FormData(form);

    // Gửi dữ liệu bằng AJAX
    try {
        const response = await fetch('/api/check_valid_user.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        // Xử lý kết quả
        if (result.errors) {
            // Xoá tất cả lỗi cũ
            const errorMessages = document.querySelectorAll('.error-message-input');
            errorMessages.forEach(errorMessage => {
                errorMessage.innerText = '';
            });

            // Xoá tất cả class error
            const inputElements = document.querySelectorAll('.form-control');
            inputElements.forEach(inputElement => {
                inputElement.classList.remove('form-input-error');
            });

            // Hiển thị lỗi
            Object.keys(result.errors).forEach(key => {
                // Lấy element chứa lỗi
                const formControlWrapper = document.querySelector(`.form-control-wrapper[data-for="${key}"]`);

                // Nếu có lỗi
                if (formControlWrapper) {
                    // Hiển thị lỗi
                    const errorElement = formControlWrapper.querySelector('.error-message-input');
                    errorElement.innerText = result.errors[key];

                    const inputElement = formControlWrapper.querySelector('.form-control[name="' + key + '"]');
                    inputElement.classList.add('form-input-error');
                }
            });
        } else {
            // Thành công thì submit form
            form.submit();
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

/*******************************************
 * 
 *         Update Profile
 * 
 * *****************************************/

document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("confirmPopup");
    const saveBtn = document.getElementById("saveBtn");
    const confirmBtn = document.getElementById("confirmBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    // Mở popup
    saveBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "block";
    });

    // Xác nhận lưu
    confirmBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "none";

        // Gọi hàm validateForm
        validateForm();
    });

    // Đóng popup
    cancelBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "none";
    });

    // Đóng popup khi click vào nhấn ra ngoài
    window.onclick = function (event) {
        if (event.target == popup) {
            popup.style.display = "none";
        }
    }
});

const updatedProfilePopupHandler = () => {
    const popup = document.getElementById("updatePopup");
    const saveBtn = document.getElementById("saveBtn");
    const confirmBtn = document.getElementById("confirmBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    // Mở popup
    saveBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "block";
    });

    // Xác nhận lưu
    confirmBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "none";
    });

    // Đóng popup
    cancelBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.style.display = "none";
    });

    // Đóng popup khi click vào nhấn ra ngoài
    window.onclick = function (event) {
        if (event.target == popup) {
            popup.style.display = "none";
        }
    }
}

/*******************************************
* 
*          CropBox
* 
*******************************************/
const getRoundedCanvas = (sourceCanvas) => {
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    const width = sourceCanvas.width;
    const height = sourceCanvas.height;

    canvas.width = width;
    canvas.height = height;

    context.imageSmoothingEnabled = true;
    context.drawImage(sourceCanvas, 0, 0, width, height);
    context.globalCompositeOperation = "destination-in";

    context.beginPath();
    context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
    context.fill();
    return canvas;
};

document.addEventListener("DOMContentLoaded", () => {
    // Box
    const cropBox = document.getElementById("cropBox");

    // Input file
    const avatarUploader = document.getElementById("avatarUploader");

    // Image
    const imageToCrop = document.getElementById('imageToCrop');
    // Image Avatar Result
    const avatarImg = document.getElementById("avatarImg");
    // Button delete avatar
    const deleteAvatarBtn = document.getElementById("deleteAvatarBtn");

    // Lấy button
    const cropBtnSave = document.getElementById("cropBtnSave");
    const cropBtnCancel = document.getElementById("cropBtnCancel");
    const cropBtnClose = document.getElementById("cropBtnClose");

    let cropper;

    // Sự kiện khi chọn file
    avatarUploader.addEventListener("change", (event) => {
        // Kiểm tra file có phải hình ảnh
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imageToCrop.src = e.target.result; // Hiển thị ảnh trong box cắt
                cropBox.style.display = "block";  // Hiển thị box cắt

                // Hình ảnh đang load
                imageToCrop.onload = () => {
                    // Hủy cropper cũ nếu có
                    if (cropper) {
                        cropper.destroy();
                    };

                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,        // Giữ hình vuông
                        viewMode: 1,           // Đảm bảo crop trong vùng ảnh
                        zoomable: false,       // Tắt chức năng zoom
                        minCropBoxWidth: 100,  // Đặt chiều rộng tối thiểu cho vùng crop
                        minCropBoxHeight: 100, // Đặt chiều cao tối thiểu cho vùng crop
                        ready: () => {
                            cropAble = true;
                            cropper.setCropBoxData({
                                height: imageToCrop.height
                            });
                        },
                    });

                };
            };
            reader.readAsDataURL(file);

        }
    });

    // Xử lý nút Save
    cropBtnSave.addEventListener("click", () => {
        if (!cropAble) {
            return;
        }

        // Crop
        const croppedCanvas = cropper.getCroppedCanvas();
        // Round
        const roundedCanvas = getRoundedCanvas(croppedCanvas);

        // Chuyển canvas thành dữ liệu URL (Base64)
        const dataUrl = roundedCanvas.toDataURL('image/png');

        // Hiển thị ảnh đã crop
        avatarImg.src = dataUrl;

        // Chuyển canvas thành blob
        roundedCanvas.toBlob((blob) => {
            const file = new File([blob], "avatar.png", { type: "image/png" });
            const dt = new DataTransfer();
            dt.items.add(file);
            avatarUploader.files = dt.files;
        });

        // Ẩn box cắt
        cropBox.style.display = "none";

        // Hủy cropper
        cropper.destroy();
    });

    // Xử lý nút cancel
    cropBtnCancel.addEventListener('click', () => {
        cropBox.style.display = "none";
        imageToCrop.value = "";
        imageToCrop.removeAttribute('src');

        cropper.destroy();
    });

    // Xử lý nút đóng
    cropBtnClose.addEventListener('click', () => {
        cropBox.style.display = "none";
        imageToCrop.value = "";
        imageToCrop.removeAttribute('src');

        cropper.destroy();
    });

    // Delete Avatar
    deleteAvatarBtn.addEventListener("click", () => {
        if (avatarImg.src !== "/admin/assets/img/avatar/img1.jpg") {
            avatarImg.src = "/admin/assets/img/avatar/img1.jpg";
        }
    });
});

