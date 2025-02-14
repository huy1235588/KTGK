const profile = document.getElementById('addUserStepProfile');
const securityInformation = document.getElementById('addUserStepSecurityInformation');
const confirmation = document.getElementById('addUserStepConfirmation');

const cardFooter = document.getElementById('btn-form');

const stepItem = document.querySelectorAll('.step-item');
const stepContentWrapper = document.querySelectorAll('a.step-content-wrapper');

const btnNextForm = document.getElementById('btnNextForm');
const btnPreviousForm = document.getElementById('btnPreviousForm');

// Toggle password visibility
document.querySelectorAll('.form-input-password').forEach(element => {
    const showPasswordBtn = element.getElementsByClassName('show-password')[0];

    showPasswordBtn.addEventListener('click', () => {
        const passwordField = element.getElementsByTagName('input')[0];

        // Chuyển đổi giữa 'password' and 'text' input
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            element.getElementsByClassName('show-password-icon')[0].style.display = "none";
            element.getElementsByClassName('hide-password-icon')[0].style.display = "block";
        } else {
            passwordField.type = 'password';
            element.getElementsByClassName('show-password-icon')[0].style.display = "block";
            element.getElementsByClassName('hide-password-icon')[0].style.display = "none";
        }
    });
});

const showProfile = () => {
    profile.style.display = "flex";
    securityInformation.style.display = "none";
    confirmation.style.display = "none";

    btnPreviousForm.style.display = "none"
    cardFooter.style.justifyContent = "flex-end";

    btnNextForm.textContent = "Next";

    profile.classList.add('active');
    confirmation.classList.remove('active');
    securityInformation.classList.remove('active');

    stepItem[0].classList.add('active');
    stepItem[1].classList.remove('active');
    stepItem[2].classList.remove('active');
};

const showSecurityInformation = () => {
    profile.style.display = "none";
    securityInformation.style.display = "flex";
    confirmation.style.display = "none";

    btnPreviousForm.style.display = "block"
    cardFooter.style.justifyContent = "space-between";

    btnNextForm.textContent = "Next";

    profile.classList.remove('active');
    securityInformation.classList.add('active');
    confirmation.classList.remove('active');

    stepItem[0].classList.remove('active');
    stepItem[1].classList.add('active');
    stepItem[2].classList.remove('active');
};

const showConfirmation = () => {
    profile.style.display = "none";
    securityInformation.style.display = "none";
    confirmation.style.display = "block";

    btnPreviousForm.style.display = "block"
    cardFooter.style.justifyContent = "space-between";

    btnNextForm.textContent = "Save";

    profile.classList.remove('active');
    securityInformation.classList.remove('active');
    confirmation.classList.add('active');

    stepItem[0].classList.remove('active');
    stepItem[1].classList.remove('active');
    stepItem[2].classList.add('active');
};

// Button previous
btnPreviousForm.addEventListener('click', () => {
    if (confirmation.classList.contains('active')) {
        showSecurityInformation();
    }

    else if (securityInformation.classList.contains('active')) {
        showProfile();
    }
});

stepContentWrapper[0].addEventListener('click', () => {
    showProfile();
});

stepContentWrapper[1].addEventListener('click', () => {
    showSecurityInformation();
});

stepContentWrapper[2].addEventListener('click', () => {
    showConfirmation();
});


/*******************************************
* 
*          Select form
* 
*******************************************/
const countrySelect = document.getElementById('countrySelect');
const countryText = document.getElementById('countryText');

countrySelect.addEventListener("change", (event) => {
    countryText.textContent = event.target.value;
});

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


/*******************************************
* 
*          Check input
* 
*******************************************/
// Các hàm kiểm tra input   
const validationRules = {
    email: (value) => {
        if (value.trim().length === 0) return "Required";
        if (!value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
            return "Invalid email";
        }
        if (value.length >= 255) return "Too long";
        return "";
    },

    userName: (value) => {
        if (value.trim().length === 0) return "Required";
        if (value.length <= 2) return "Too short";
        if (value.match(/\s/)) return "Invalid format";

        return "";
    },

    password: (value) => {
        if (value.trim().length === 0) return "Required";
        if (value.length <= 6) return "Too short";
        if (!/^(?=.*[a-zA-Z])(?=.*\d)(?!.*\s).+$/.test(value)) return "Invalid format";
        if (value.length >= 255) return "Too long";
        return "";
    },

    confirmPassword: (value) => {
        const password = document.getElementById('passwordInput').value;
        if (value.trim().length === 0) return "Required";
        if (value !== password) return "Passwords do not match";
        return "";
    },

    avatar: () => {

    },

    default: (value) => {
        return value.trim() ? "" : "Required";
    },
};

// Hàm xác nhận form
const confirmForm = {
    email: (value) => {
        document.getElementById('confirmEmail').textContent = value
    },

    username: (value) => {
        document.getElementById('confirmUsername').textContent = value;
    },

    password: (value) => {
        // Ẩn password
        var hiddenContent = value.replace(/./g, "•");

        document.getElementById('confirmPassword').textContent = hiddenContent;
    },

    firstName: (value, element) => {
        const lastName = element.nextElementSibling.value
        document.getElementById('confirmFullName').textContent = value + " " + lastName;
    },

    phone: (value) => {
        document.getElementById('confirmPhone').textContent = value;
    },

    country: () => {
        document.getElementById('confirmCountry').textContent = document.getElementById("countryText").textContent;
    },

    role: (value, element) => {
        // console.log(element)
        // element.closet(".col-form-radio").querySelectorAll(".form-control").forEach(el => {
        document.getElementById('confirmRole').textContent = value; 0
        // });
    },

    avatar: () => {
        document.getElementById("confirmAvatar").src = document.getElementById("avatarImg").src;
    },

    default: () => {
    },
}

// Hàm tạo element lỗi
const createErrorMessage = (element, message) => {
    let errorMessage = element.parentElement.querySelector(`.error-message-input[data-error-for="${element.name}"]`);

    // Chỉ thêm errorMessage nếu nó chưa tồn tại
    if (!errorMessage) {
        // Áp dụng css lỗi
        element.classList.add("form-input-error");

        // Thêm nội dung lỗi
        errorMessage = document.createElement('span');              // Tạo element
        errorMessage.textContent = message;                      // Thêm nội dung
        errorMessage.classList.add("error-message-input");          // Thêm class
        errorMessage.setAttribute("data-error-for", element.name);  // Liên kết với input thông qua 'name'
        element.parentElement.append(errorMessage);
    }
}

// Tìm toàn bộ input
const inputForm = document.querySelectorAll(".col-form-input input");

// Gắn sự kiện cho từng input
inputForm.forEach(element => {
    // Sự kiện input khi mất focus
    element.addEventListener("blur", () => {
        const validate = validationRules[element.name] || validationRules.default;
        const errorMessage = validate(element.value);
        if (errorMessage) {
            createErrorMessage(element, errorMessage);
        }
    });

    // Xóa errorMessage khi focus
    element.addEventListener("focus", () => {
        const errorMessage = element.parentElement.querySelector(`.error-message-input[data-error-for="${element.name}"]`);
        if (errorMessage) {
            errorMessage.remove();
        }

        // Xóa css lỗi
        element.classList.remove("form-input-error");
    });
});

// Hàm kiểm tra toàn bộ input
const checkAllInputForm = (inputForm) => {
    // Kiểm tra từng input
    inputForm.forEach(element => {
        const validate = validationRules[element.name] || validationRules.default;
        const errorMessage = validate(element.value);
        if (errorMessage) {
            createErrorMessage(element, errorMessage);
        }

        // Xóa errorMessage khi focus
        element.addEventListener("focus", () => {
            const errorMessage = element.parentElement.querySelector(`.error-message-input[data-error-for="${element.name}"]`);
            if (errorMessage) {
                errorMessage.remove();
            }

            // Xóa css lỗi
            element.classList.remove("form-input-error");
        });
    });
}

// Nút next
btnNextForm.addEventListener('click', () => {
    if (profile.classList.contains('active')) {
        // Tìm toàn bộ input
        const inputForm = document.querySelectorAll("#addUserStepProfile .col-form-input input");

        // Kiểm tra toàn bộ input
        checkAllInputForm(inputForm);

        // Nếu có input không hợp lệ
        if (document.querySelector("#addUserStepProfile .error-message-input")) {
            showProfile();
        }

        // Nếu hợp lệ thì chuyển đến form tiếp theo
        else {
        }
        showSecurityInformation();
    }
    else if (securityInformation.classList.contains('active')) {
        // Tìm toàn bộ input
        const inputForm = document.querySelectorAll("#addUserStepSecurityInformation .col-form-input input");

        // Kiểm tra toàn bộ input
        checkAllInputForm(inputForm);

        // Nếu có input không hợp lệ
        if (document.querySelector("#addUserStepSecurityInformation .error-message-input")) {
            showSecurityInformation();
        }

        // Nếu hợp lệ thì chuyển đến form tiếp theo
        else {

            const allInputForm = document.querySelectorAll(".col-form-input input");

            // Lặp qua từng phần tử và gọi hàm xác nhận thích hợp
            allInputForm.forEach(element => {
                const handler = confirmForm[element.name] || confirmForm.default;

                handler(element.value, element);
                confirmForm.country();
            });

        }
        showConfirmation();
    }
    else if (confirmation.classList.contains('active')) {
        // Xác nhận form
        const form = document.getElementById('addUserForm');
        form.submit();
    }
});