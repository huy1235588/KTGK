// Ẩn thông báo sau 5 giây
setTimeout(function () {
    var alertBox = document.querySelector('.notification');
    if (alertBox) {
        alertBox.style.display = 'none';
    }
}, 5000);