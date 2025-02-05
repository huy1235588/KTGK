/*********************************
 
    Dropdown Select Single

*********************************/

// Icon của checkbox
const CHECKBOX_ICON = '<path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>';
const CHECKBOX_OUTLINE_ICON = '<path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>';

let selectedItemsMap = new Map();

// Hàm cập nhật giá trị đã chọn
function updateSelection(formGroupSelect, value) {
    const formControl = formGroupSelect.querySelector('.form-control-select');
    const selectBox = formGroupSelect.querySelector('.select-box');
    const dropdownItems = [...formGroupSelect.querySelectorAll('.dropdown-select-menu .dropdown-select-item')];

    console.log(value);

    // Hiển thị value đã chọn
    selectBox.textContent = value;
    formControl.value = value;

    // Trigger event input
    formControl.dispatchEvent(new Event('input'));

    // Cập nhật icon cho các item
    dropdownItems.forEach(item => {
        // Kiểm tra xem item đã chọn chưa
        const isSelected = item.textContent.trim() === value;

        // Thêm class selected cho item đã chọn
        item.classList.toggle('selected', isSelected);

        // Đổi svg của checkbox icon của item đã chọn
        item.querySelector('.dropdown-select-icon').innerHTML = isSelected ? CHECKBOX_ICON : CHECKBOX_OUTLINE_ICON;
    });
}

// Hàm dropdownSelect
function dropdownSelect() {
    document.querySelectorAll('.form-group-select').forEach(formGroupSelect => {
        // Lấy ra các element cần thiết
        const formControl = formGroupSelect.querySelector('.form-control-select');
        const dropdownButton = formGroupSelect.querySelector('.form-control-wrapper.select');
        const dropdownMenu = formGroupSelect.querySelector('.dropdown-select-menu');
        const dropdownItems = [...formGroupSelect.querySelectorAll('.dropdown-select-menu .dropdown-select-item')];

        // Hiển thị dropdown menu khi click vào dropdown button
        dropdownButton.addEventListener('click', () => dropdownMenu.classList.toggle('show'));

        // Ẩn dropdown menu khi click ra ngoài dropdown button và dropdown menu
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        // Chọn một item trong dropdown menu
        dropdownItems.forEach(item => {
            // Xử lý sự kiện khi click vào item
            item.addEventListener('click', function () {
                // Lấy ra value của item đã chọn
                const value = item.textContent.trim();

                // Cập nhật giá trị đã chọn
                updateSelection(formGroupSelect, value);

                // Ẩn dropdown menu
                dropdownMenu.classList.remove('show');
            });
        });
    });
}

/*********************************

    Dropdown Select Multiple

*********************************/

// Danh sách item đã chọn
let selectedItemsMutipleMap = new Map();

// Hàm cập nhật selectedItems
function updateSelectedItems(formGroupSelect) {
    const selectBox = formGroupSelect.querySelector('.select-box');
    const formControl = formGroupSelect.querySelector('.form-control-select');
    const placeholder = selectBox.getAttribute('data-placeholder');
    const formControlId = formControl.getAttribute('id');

    // Lấy ra selectedItems từ selectedItemsMutipleMap
    const selectedItems = selectedItemsMutipleMap.get(formControlId) || new Set();

    // Hiển thị selected items
    selectBox.innerHTML = selectedItems.size
        ? `<div class="selected-items">
            ${[...selectedItems].map(item => `
            <div class="selected-item" data-value="${item}">
                <span>${item}</span>
                <button class="remove-selected-item" type="button">✕</button>
            </div>`).join('')}
        </div>`
        : `<em style="color: gray;">${placeholder}</em>`;

    // Cập nhật value cho formControl
    formControl.value = [...selectedItems].join(', ');

    // Trigger event input
    formControl.dispatchEvent(new Event('input'));
}

// Hàm dropdownSelectMultiple
function dropdownSelectMultiple() {
    document.querySelectorAll('.form-group-select-multiple').forEach(formGroupSelect => {
        // Lấy ra các element cần thiết
        const formControl = formGroupSelect.querySelector('.form-control-select');
        const formControlId = formControl.getAttribute('id');
        const selectBox = formGroupSelect.querySelector('.select-box');
        const dropdownButton = formGroupSelect.querySelector('.form-control-wrapper.select');
        const dropdownMenu = formGroupSelect.querySelector('.dropdown-select-menu');
        const dropdownItems = [...formGroupSelect.querySelectorAll('.dropdown-select-menu .dropdown-select-item')];

        // Ô search
        const search = formGroupSelect.querySelector('.filter-select-search input');

        // Hiển thị dropdown menu khi click vào dropdown button
        function toggleDropdown() {
            dropdownMenu.classList.toggle('show');
        }

        // Xử lý sự kiện khi click vào dropdown button
        function handleClickOutside(event) {
            if (!formGroupSelect.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        }

        // Xử lý sự kiện khi nhập vào ô search
        function handleSearch(event) {
            const value = event.target.value.trim().toUpperCase();
            dropdownItems.forEach(item => {
                // Lấy ra text của item
                const text = item.textContent.trim().toUpperCase();

                // Ẩn hiện item dựa trên giá trị search
                item.style.display = text.includes(value) ? 'flex' : 'none';
            });
        }

        // Xử lý sự kiện khi click vào item
        function handleItemClick(event) {
            // Lấy ra item đã click
            const item = event.target.closest('.dropdown-select-item');
            if (!item) return;

            // Lấy ra value của item đã chọn
            const value = item.querySelector('.dropdown-select-item-text span').textContent.trim();

            // Lấy ra selectedItems từ selectedItemsMutipleMap
            const selectedItems = selectedItemsMutipleMap.get(formControlId) || new Set();

            // Nếu item đã chọn thì bỏ chọn, ngược lại thì chọn
            if (selectedItems.has(value)) {
                // Xóa item khỏi selectedItems
                selectedItems.delete(value);

                // Xóa class selected của item đã chọn
                item.classList.remove('selected');

                // Đổi icon của item đã chọn
                item.querySelector('.dropdown-select-icon').innerHTML = CHECKBOX_OUTLINE_ICON;
            } else {
                // Thêm item vào selectedItems
                selectedItems.add(value);

                // Thêm class selected cho item đã chọn
                item.classList.add('selected');

                // Đổi icon của item đã chọn
                item.querySelector('.dropdown-select-icon').innerHTML = CHECKBOX_ICON;
            }

            // Lưu selectedItems vào selectedItemsMutipleMap
            selectedItemsMutipleMap.set(formControlId, selectedItems);

            // Cập nhật selectedItems
            updateSelectedItems(formGroupSelect);
        }

        // Xử lý sự kiện khi click vào nút xóa item đã chọn
        function handleRemoveSelected(event) {
            // Lấy ra nút xóa item đã chọn
            const button = event.target.closest('.remove-selected-item');
            if (!button) return;

            // Lấy ra item đã chọn
            const selectedItem = button.closest('.selected-item');
            // Lấy ra value của item đã chọn
            const value = selectedItem.dataset.value;

            // Lấy ra selectedItems từ selectedItemsMutipleMap
            const selectedItems = selectedItemsMutipleMap.get(formControlId) || new Set();

            // Xóa item khỏi selectedItems
            selectedItems.delete(value);
            // Cập nhật selectedItems
            updateSelectedItems(formGroupSelect);

            // Bỏ chọn item trong dropdown menu
            dropdownItems.forEach(item => {
                // Nếu item đã chọn thì bỏ chọn
                if (item.querySelector('.dropdown-select-item-text span').textContent.trim() === value) {
                    // Xóa class selected của item đã chọn
                    item.classList.remove('selected');
                    // Đổi icon của item đã chọn
                    item.querySelector('.dropdown-select-icon').innerHTML = CHECKBOX_OUTLINE_ICON;
                }
            });

            // Lưu selectedItems vào selectedItemsMutipleMap
            selectedItemsMutipleMap.set(formControlId, selectedItems);
        }

        // Gọi các hàm xử lý sự kiện
        dropdownButton.addEventListener('click', toggleDropdown);
        document.addEventListener('click', handleClickOutside);
        dropdownMenu.addEventListener('click', handleItemClick);
        selectBox.addEventListener('click', handleRemoveSelected);

        // Xử lý sự kiện khi nhập vào ô search
        if (search) search.addEventListener('input', handleSearch);

        // Cập nhật selectedItems
        updateSelectedItems(formGroupSelect);
    });
}

/***************************************
 
    Lưu dữ liệu form vào localStorage

**************************************/

// Hàm lưu dữ liệu form vào localStorage
function saveFormData() {
    const form = document.getElementById('productAddForm');
    // Lấy ra tất cả các input có class bắt đầu form-control-
    const formElements = form.querySelectorAll('input[class^="form-control"], textarea[class^="form-control"]');

    // Lấy dữ liệu từ localStorage (nếu có)
    const savedData = JSON.parse(localStorage.getItem('productFormData')) || {};

    // Lặp qua từng element để lấy dữ liệu
    formElements.forEach(inputForm => {
        // Lấy ra name của inputForm
        const selectedName = inputForm.getAttribute('name');

        // Hiển thị dữ liệu đã lưu (nếu có)
        if (savedData[selectedName]) {
            // Xử lý riêng cho dropdown-select
            if (inputForm.classList.contains('form-control-select-single')) {
                // Lấy ra formGroupSelect
                const formGroupSelect = inputForm.closest('.form-group-select');

                // Lấy ra selectedValue từ savedData
                const selectedValue = savedData[selectedName];

                // Nếu savedData[selectedName] tồn tại thì cập nhật selectedValue
                if (selectedValue) {
                    // Cập nhật selectedValue
                    updateSelection(formGroupSelect, selectedValue);
                }
            }

            // Xử lý riêng cho dropdown-select-multiple
            else if (inputForm.classList.contains('form-control-select-multiple')) {
                // Lấy ra formGroupSelect
                const formGroupSelect = inputForm.closest('.form-group-select-multiple');
                const formControlId = inputForm.getAttribute('id');

                // Lấy ra selectedItems từ savedData
                let selectedItems = new Set();

                // Nếu savedData[selectedName] tồn tại thì thêm các item vào selectedItems
                for (const item of savedData[selectedName].split(', ')) {
                    // Thêm item vào selectedItems
                    selectedItems.add(item);

                    // Lấy ra tất cả các dropdownItem
                    const dropdownItems = formGroupSelect.querySelectorAll(`#${formControlId} .dropdown-select-menu .dropdown-select-item`);

                    // Tìm dropdownItem có thẻ span chứa text giống với item
                    const matchingDropdownItem = [...dropdownItems].find(dropdownItem => dropdownItem.querySelector('.dropdown-select-item-text span').textContent.trim() === item);

                    // Nếu dropdownItem tồn tại thì thêm class selected và icon cho dropdownItem
                    if (matchingDropdownItem) {
                        // Thêm class selected cho item đã chọn
                        matchingDropdownItem.classList.add('selected');

                        // Đổi icon của item đã chọn
                        matchingDropdownItem.querySelector('.dropdown-select-icon').innerHTML = CHECKBOX_ICON;
                    }
                }

                // Lưu selectedItems vào selectedItemsMutipleMap
                selectedItemsMutipleMap.set(formControlId, selectedItems);

                // Cập nhật selectedItems
                updateSelectedItems(formGroupSelect);
            }

            else {
                // Hiển thị dữ liệu đã lưu
                inputForm.value = savedData[selectedName];
            }
        }

        // Sử dụng sự kiện input để lấy dữ liệu ngay khi người dùng nhập liệu
        inputForm.addEventListener('input', function () {
            // Cập nhật object với dữ liệu mới
            savedData[selectedName] = inputForm.value;

            // Lưu dữ liệu vào localStorage
            localStorage.setItem('productFormData', JSON.stringify(savedData));
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    dropdownSelect();
    dropdownSelectMultiple();
    saveFormData();
});