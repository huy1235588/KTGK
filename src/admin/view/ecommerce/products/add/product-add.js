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



/***************
 
    Checkbox
 
***************/
function handleCheckbox() {
    const formGroupCheckbox = document.querySelectorAll('.form-group-checkbox');

    formGroupCheckbox.forEach(formGroup => {
        const formControl = formGroup.querySelector('.form-control-checkbox');
        const checkboxIcon = formGroup.querySelector('.checkbox-icon');
        const formControlWrapper = formGroup.querySelector('.form-control-wrapper');

        // Hiển thị icon checkbox và thêm class checked cho formControlWrapper khi checked
        formControl.addEventListener('change', function () {
            checkboxIcon.innerHTML = formControl.checked ? CHECKBOX_ICON : CHECKBOX_OUTLINE_ICON;
            formControlWrapper.classList.toggle('checked', formControl.checked);
        });
    });
}

/********************
 
    File Upload
 
*********************/
// Hàm hiển thị file preview
function displayFilePreview(formGroup, file, uploaderContainer, formControl) {
    // Lấy ra các element cần thiết
    const filePreview = formGroup.querySelector('.file-preview');
    const filePreviewImage = formGroup.querySelector('.file-preview-image');
    const filePreviewRemove = formGroup.querySelector('.file-preview-remove');
    const filePreviewName = formGroup.querySelector('.file-preview-name');
    const filePreviewSize = formGroup.querySelector('.file-preview-size');

    // Hiển thị file preview
    uploaderContainer.style.display = 'none';
    filePreview.style.display = 'flex';
    filePreviewName.textContent = file.name;
    filePreviewSize.textContent = `${(file.size / 1024).toFixed(2)} KB`;

    // Nếu file là url 
    if (typeof file === 'string') {
        filePreviewName.textContent = file.split('/').pop();
        filePreviewImage.src = file;

        // Đổi formControl thành text
        formControl.type = 'text';
        formControl.value = file;
    }

    // Hiển thị file preview image nếu file là hình ảnh
    else if (file.type && file.type.includes('image')) {
        // Tạo đường dẫn cho file preview image
        const reader = new FileReader();

        // Hiển thị file preview image
        reader.onload = function (event) {
            filePreviewImage.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Xử lý sự kiện khi click vào nút xoá file
    filePreviewRemove.addEventListener('click', function () {
        formControl.value = '';
        filePreview.style.display = 'none';
        uploaderContainer.style.display = 'block';

        // Đổi formControl thành file
        formControl.type = 'file';
    });
}

// Hàm xử lý file upload
function handleFileUpload() {
    // Lặp qua từng formGroup
    document.querySelectorAll('.form-group-file').forEach(formGroup => {
        const uploaderContainer = formGroup.querySelector('.file-uploader-container');
        const formControl = formGroup.querySelector('.form-control-file');

        // Hiển thị file đã chọn
        formControl.addEventListener('change', function () {
            const file = formControl.files[0];

            // Hiển thị file preview
            if (file) {
                displayFilePreview(formGroup, file, uploaderContainer, formControl);
            }
        });

        // Hàm xử lý submit popup
        async function handelSubmitPopup(value) {
            // Hiển thị file preview
            if (value) {
                // Hiển thị file preview
                displayFilePreview(formGroup, value, uploaderContainer, formControl);

                // Đóng popup
                myPopup.close();
            }
        }

        // Tạo popup
        const myPopup = new PopupInput({
            header: "Add image from URL",
            label: "Paste image URL",
            inputName: "imageUrl",
            inputType: "text",
            buttonText: "Add image",
            placeholder: "Enter image URL",
            onSubmit: handelSubmitPopup
        });

        // Lấy ra nút add image from URL
        const addImageFromUrlBtn = formGroup.querySelector('.form-control-url-btn');
        addImageFromUrlBtn.addEventListener('click', () => myPopup.open());
    });
}


/***************************************

    Custom textarea

***************************************/
// Khai báo biến quill
let quill;

// Hàm custom textarea
function textareaCustom() {
    const toolbarQuill = [
        ['bold', 'italic', 'underline', 'strike'], // Chữ đậm, nghiêng, gạch chân, gạch ngang
        [{
            'color': []
        }, {
            'background': []
        }], // Màu chữ và màu nền
        [{
            'script': 'sub'
        }, {
            'script': 'super'
        }], // Chỉ số dưới và chỉ số trên
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }], // Tiêu đề
        [{
            'align': []
        }], // Căn lề
        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }], // Danh sách có thứ tự và không thứ tự
        [{
            'indent': '-1'
        }, {
            'indent': '+1'
        }], // Thụt lề
        ['blockquote', 'code-block'], // Trích dẫn và khối mã
        ['link', 'image', 'video', 'customImage'], // Chèn liên kết, hình ảnh, video, hình ảnh từ URL
        ['clean'] // Xóa định dạng
    ]

    // Hàm xử lý custom image
    function imageHandler() {
        const tooltip = this.quill.theme.tooltip;
        const originalSave = tooltip.save;
        const originalHide = tooltip.hide;

        tooltip.save = function () {
            const range = this.quill.getSelection(true);
            const value = this.textbox.value;
            if (value) {
                this.quill.insertEmbed(range.index, 'image', value, 'user');
            }
        };
        // Called on hide and save.
        tooltip.hide = function () {
            tooltip.save = originalSave;
            tooltip.hide = originalHide;
            tooltip.hide();
        };
        tooltip.edit('image');
        tooltip.textbox.placeholder = 'Image URL';
    }

    // Khởi tạo quill editor
    quill = new Quill('#quill-editor', {
        placeholder: 'Type your details...', // Placeholder
        theme: 'snow', // Theme
        modules: {
            // Toolbar
            toolbar: {
                container: toolbarQuill, // Toolbar
                handlers: {
                    customImage: imageHandler // Custom image
                }
            }
        }
    });

    // Chờ Quill khởi tạo xong
    setTimeout(() => {
        // Chọn nút customImage trong toolbar
        const customImageButton = document.querySelector('.ql-customImage');

        if (customImageButton) {
            // Xóa nội dung mặc định của nút
            customImageButton.innerHTML = '';

            // Thêm SVG vào nút
            customImageButton.innerHTML = `
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1" 
                viewBox="0 0 48 48" enable-background="new 0 0 48 48" height="18px" width="18px" 
                xmlns="http://www.w3.org/2000/svg">
                <path fill="#8CBCD6" d="M40,41H8c-2.2,0-4-1.8-4-4V11c0-2.2,1.8-4,4-4h32c2.2,0,4,1.8,4,4v26C44,39.2,42.2,41,40,41z"></path>
                <circle fill="#B3DDF5" cx="35" cy="16" r="3"></circle>
                <polygon fill="#9AC9E3" points="20,16 9,32 31,32"></polygon>
                <polygon fill="#B3DDF5" points="31,22 23,32 39,32"></polygon>
                <circle fill="#43A047" cx="38" cy="38" r="10"></circle>
                <g fill="#fff">
                    <rect x="36" y="32" width="4" height="12"></rect>
                    <rect x="32" y="36" width="12" height="4"></rect>
                </g>
            </svg>
        `;
        }
    }, 10);

    // Lắng nghe sự kiện input để lưu dữ liệu
    quill.on('text-change', function () {
        // Lấy ra nội dung của quill editor
        const quillContent = quill.root.innerHTML;

        // Lấy ra formControl
        const formGroup = document.querySelector('.form-group-quill');
        const formControl = formGroup.querySelector('.form-control-quill');

        // Cập nhật value cho formControl
        formControl.value = quillContent;
        
        // Trigger event input
        formControl.dispatchEvent(new Event('input'));
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

            // Xử lý riêng cho checkbox
            else if (inputForm.classList.contains('form-control-checkbox')) {
                // Hiển thị dữ liệu đã lưu
                if (savedData[selectedName]) {
                    // Cập nhật checked cho inputForm
                    inputForm.checked = savedData[selectedName] === 1;

                    // Lấy ra formGroupCheckbox
                    const formGroupCheckbox = inputForm.closest('.form-group-checkbox');

                    // Cập nhật icon checkbox và class checked cho formControlWrapper
                    const checkboxIcon = formGroupCheckbox.querySelector('.checkbox-icon');
                    const formControlWrapper = formGroupCheckbox.querySelector('.form-control-wrapper');
                    checkboxIcon.innerHTML = savedData[selectedName] ? CHECKBOX_ICON : CHECKBOX_OUTLINE_ICON;

                    // Thêm class checked cho formControlWrapper
                    formControlWrapper.classList.toggle('checked', savedData[selectedName]);
                }
            }

            // Xử lý riêng cho file upload
            else if (inputForm.classList.contains('form-control-file')) {

            }

            // Xử lý riêng cho quill editor
            else if (inputForm.classList.contains('form-control-quill')) {
                // Hiển thị dữ liệu đã lưu
                quill.root.innerHTML = savedData[selectedName];
            }

            else {
                // Hiển thị dữ liệu đã lưu
                inputForm.value = savedData[selectedName];
            }
        }

        // Sự kiện change để lưu dữ liệu ngay khi người dùng chọn giá trị
        if (inputForm.type === 'checkbox') {
            inputForm.addEventListener('change', function () {
                // Cập nhật object với dữ liệu mới
                savedData[selectedName] = inputForm.type === 'checkbox' ? (inputForm.checked ? 1 : 0) : inputForm.value;

                // Lưu dữ liệu vào localStorage
                localStorage.setItem('productFormData', JSON.stringify(savedData));
            });
        }

        // Sự kiện change để lưu dữ liệu ngay khi người dùng chọn giá trị
        else if (inputForm.type === 'file') {

        }

        else {
            // Sử dụng sự kiện input để lấy dữ liệu ngay khi người dùng nhập liệu
            inputForm.addEventListener('input', function () {
                // Cập nhật object với dữ liệu mới
                savedData[selectedName] = inputForm.value;

                // Lưu dữ liệu vào localStorage
                localStorage.setItem('productFormData', JSON.stringify(savedData));
            });
        }
    });
}

/***************************************
 
    Xoá error message khi nhập liệu

**************************************/
function removeErrorMessagesOnInput() {
    // Lấy ra tất cả các form-group
    const formElements = document.querySelectorAll('.form-group:not(.form-group-flex):not(.form-group-submit)');

    formElements.forEach(formElement => {
        // Lấy ra tất cả các input có class bắt đầu form-control-
        const formControl = formElement.querySelector('input[class^="form-control"], textarea[class^="form-control"]');

        // Xoá element error-message khi nhập liệu
        if (!formControl) return;
        formControl.addEventListener('input', function () {
            const errorMessage = formElement.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
            }

            // Xoá class error cho form-control-outline
            const formControlOutLine = formElement.querySelector('.form-control-outline');
            if (formControlOutLine) {
                formControlOutLine.classList.remove('error');
            }
        });
    });
}

// Sử dụng DOMContentLoaded để chờ tất cả các element được load xong
document.addEventListener('DOMContentLoaded', () => {
    dropdownSelect();
    dropdownSelectMultiple();
    handleCheckbox();
    handleFileUpload();
    textareaCustom();
    saveFormData();
    removeErrorMessagesOnInput();
});