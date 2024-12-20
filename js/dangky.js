const form = document.getElementById("registerForm");

form.addEventListener("submit", function (event) {
    event.preventDefault();

    let isValid = true;

    // Lấy giá trị các input
    const name = document.getElementById("name");
    const phone = document.getElementById("phone");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");

    // Reset trạng thái lỗi
    document.querySelectorAll(".error-message").forEach(el => el.textContent = "");
    document.querySelectorAll("input").forEach(el => el.classList.remove("error"));

    // Kiểm tra họ tên
    if (name.value.trim() === "") {
        isValid = false;
        name.classList.add("error");
        document.getElementById("nameError").textContent = "Họ tên không được để trống.";
    }

    // Kiểm tra số điện thoại
    if (!/^\d+$/.test(phone.value.trim())) {
        isValid = false;
        phone.classList.add("error");
        document.getElementById("phoneError").textContent = "Số điện thoại phải là số.";
    }

    // Kiểm tra email
    if (!/\S+@\S+\.\S+/.test(email.value.trim())) {
        isValid = false;
        email.classList.add("error");
        document.getElementById("emailError").textContent = "Email không hợp lệ.";
    }

    // Kiểm tra mật khẩu
    if (password.value.trim().length < 6) {
        isValid = false;
        password.classList.add("error");
        document.getElementById("passwordError").textContent = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    // Kiểm tra nhập lại mật khẩu
    if (confirmPassword.value !== password.value) {
        isValid = false;
        confirmPassword.classList.add("error");
        document.getElementById("confirmPasswordError").textContent = "Mật khẩu nhập lại không khớp.";
    }

    // Nếu hợp lệ, gửi form
    if (isValid) {
        alert("Đăng ký thành công!");
        form.submit();
    }
});
