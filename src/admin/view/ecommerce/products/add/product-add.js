function dropdownSelect() {
    // Lấy ra các element cần thiết
    const formGroupSelects = document.querySelectorAll('.form-group-select');

    formGroupSelects.forEach(formGroupSelect => {
        // Lấy ra các element cần thiết
        const formControl = formGroupSelect.querySelector('.form-control-select');
        const selectBox = formGroupSelect.querySelector('.select-box');
        const dropdownButton = formGroupSelect.querySelector('.form-control-wrapper.select');
        const dropdownMenu = formGroupSelect.querySelector('.dropdown-select-menu');
        const dropdownItems = formGroupSelect.querySelectorAll('.dropdown-select-menu .dropdown-select-item');
        const dropdownSelectIcon = formGroupSelect.querySelectorAll('.dropdown-select-icon');

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
            const formControlValue = formControl.value.trim();
            const itemValue = item.querySelector('.dropdown-select-item-text span').textContent.trim();

            // Nếu item đã chọn thì thêm class selected cho item đó
            if (formControlValue === itemValue) {
                item.classList.add('selected');
                item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxIcon;
                selectBox.textContent = itemValue;
            }

            // Xử lý sự kiện khi click vào item
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

                // Đổi svg của checkbox icon của item đã chọn
                item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxIcon;

                // Hiển thị value đã chọn
                selectBox.textContent = value;
            });
        });
    });
}


function dropdownSelectMultiple() {
    // Hàm render selected items
    function renderSelectedItems(selectedItems) {
        return `
            <div class="selected-items">
                ${selectedItems.map(item => `
                    <div class="selected-item" data-value="${item.trim()}">
                        <span>${item.trim()}</span>
                        <button class="remove-selected-item" type="button">
                           <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="MuiChip-deleteIcon MuiChip-deleteIconMedium MuiChip-deleteIconColorDefault MuiChip-deleteIconFilledColorDefault" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="m289.94 256 95-95A24 24 0 0 0 351 127l-95 95-95-95a24 24 0 0 0-34 34l95 95-95 95a24 24 0 1 0 34 34l95-95 95 95a24 24 0 0 0 34-34z"></path>
                           </svg>
                        </button>
                    </div>
                `).join('')}
            </div>
        `
    }

    // Lấy ra các element cần thiết
    const formGroupSelects = document.querySelectorAll('.form-group-select-multiple');

    formGroupSelects.forEach(formGroupSelect => {
        // Lấy ra các element cần thiết
        const formControl = formGroupSelect.querySelector('.form-control-select');
        const selectBox = formGroupSelect.querySelector('.select-box');
        const dropdownButton = formGroupSelect.querySelector('.form-control-wrapper.select');
        const dropdownMenu = formGroupSelect.querySelector('.dropdown-select-menu');
        const dropdownItems = formGroupSelect.querySelectorAll('.dropdown-select-menu .dropdown-select-item');

        const CheckBoxIcon = '<path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>';
        const CheckBoxOutlineBlankIcon = '<path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>';

        // Danh sách item đã chọn
        const selectedItems = [];

        // Xử lý sự kiện khi click vào option
        const search = formGroupSelect.querySelector('.filter-select-search input');
        if (search) {
            // Xử lý sự kiện khi nhập vào ô search
            search.addEventListener('input', () => {
                const value = search.value.trim().toUpperCase();

                // Lặp qua từng option để tìm kiếm
                dropdownItems.forEach(option => {
                    const text = option.textContent || option.innerText;
                    const match = text.toUpperCase().includes(value);

                    // Hiển thị hoặc ẩn option tùy theo kết quả tìm kiếm
                    if (match) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });
        }

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

        // Hàm cập nhật selectedItems
        function updateSelectedItems() {
            const placeholder = selectBox.getAttribute('data-placeholder');
            selectBox.innerHTML = selectedItems.length > 0
                ? renderSelectedItems(selectedItems)
                : `<em style="color: gray;">${placeholder}</em>`;
            formControl.value = selectedItems.join(', ');
        }

        // Lấy ra các item đã chọn
        const formControlValue = formControl.value.split(',').map(i => i.trim());

        // Chọn một item trong dropdown menu
        dropdownItems.forEach(item => {
            const itemValue = item.querySelector('.dropdown-select-item-text span').textContent.trim();

            // Nếu item đã chọn thì thêm class selected cho item đó
            if (formControlValue.includes(itemValue)) {
                item.classList.add('selected');
                item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxIcon;
                selectedItems.push(itemValue);
            }

            // Cập nhật selectedItems
            updateSelectedItems();

            // Xử lý sự kiện khi click vào item
            item.addEventListener('click', function () {
                // Lấy ra value của item đã chọn
                const value = item.querySelector('.dropdown-select-item-text span').textContent.trim();

                // Kiểm tra xem item đã chọn có trong selectedItems chưa
                const index = selectedItems.indexOf(value);

                // Nếu chưa có thì thêm vào, ngược lại thì xóa ra khỏi selectedItems
                if (index === -1) {
                    selectedItems.push(value);

                    // Thêm class selected cho item đã chọn
                    item.classList.toggle('selected');

                    // Đổi svg của checkbox icon của item đã chọn
                    item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxIcon;

                } else {
                    selectedItems.splice(index, 1);

                    // Xóa class selected của item đã chọn
                    item.classList.remove('selected');

                    // Đổi svg của checkbox icon của item đã chọn
                    item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxOutlineBlankIcon;
                }

                // Cập nhật selectedItems
                updateSelectedItems();
            });
        });

        // Xóa một item trong selectedItems
        selectBox.addEventListener('click', function (event) {
            // Lấy ra button remove
            const removeButton = event.target.closest('.remove-selected-item');
            // Nếu có button remove thì xóa item đã chọn
            if (removeButton) {
                // Ngăn chặn sự kiện click lan truyền
                event.stopPropagation();

                // Lấy ra item đã chọn
                const selectedItem = event.target.closest('.selected-item');
                const selectedItemText = selectedItem.dataset.value;
                const index = selectedItems.indexOf(selectedItemText);

                if (index !== -1) {
                    selectedItems.splice(index, 1);

                    // Cập nhật selectedItems
                    updateSelectedItems();

                    // Xóa class selected của item đã chọn
                    dropdownItems.forEach(item => {
                        if (item.querySelector('.dropdown-select-item-text span').textContent === selectedItemText) {
                            item.classList.remove('selected');
                            item.querySelector('.dropdown-select-icon').innerHTML = CheckBoxOutlineBlankIcon;
                        }
                    });
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    dropdownSelect();
    dropdownSelectMultiple();
});