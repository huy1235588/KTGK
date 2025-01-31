function f() {
    // Lấy ra các element cần thiết
    const formControl = document.querySelector('.form-control-select');
    const selectBox = document.querySelector('.select-box');
    const dropdownButton = document.querySelector('.form-control-wrapper.select');
    const dropdownMenu = document.querySelector('.dropdown-select-menu');
    const dropdownItems = document.querySelectorAll('.dropdown-select-menu .dropdown-select-item');
    const dropdownSelectIcon = document.querySelectorAll('.dropdown-select-icon');

    const CheckBoxIcon = '<path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>';
    const CheckBoxOutlineBlankIcon = '<path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>';

    // Hiển thị dropdown menu khi click vào dropdown button
    dropdownButton.addEventListener('click', function () {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Ẩn dropdown menu khi click ra ngoài dropdown button và dropdown menu
    document.addEventListener('click', function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Chọn một item trong dropdown menu
    dropdownItems.forEach(item => {
        item.addEventListener('click', function () {
            // Lấy ra value của item đã chọn
            const value = item.querySelector('.dropdown-select-item-text span').textContent;
            formControl.value = value.trim();

            // Ẩn dropdown menu
            dropdownMenu.style.display = 'none';

            // Thêm class selected cho item đã chọn
            dropdownItems.forEach(item => item.classList.remove('selected'));
            item.classList.add('selected');

            // Đổi svg của checkbox icon
            dropdownSelectIcon.forEach(icon => {
                icon.innerHTML = CheckBoxOutlineBlankIcon;
            });

            item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxIcon;

            // Hiển thị value đã chọn
            selectBox.textContent = value;
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    f();
});