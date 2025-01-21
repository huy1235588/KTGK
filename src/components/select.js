class Select {
    constructor(container, options = [], placeholder = "Select an option", option = {}) {
        this.container = container;
        this.options = options;
        this.placeholder = placeholder;
        this.selectedValue = option.value || null;
        this.width = option.width || "200px";

        // Hàm callback khi chọn một tùy chọn
        this.onChange = option.onChange || function() {};
        this.init();
    }

    init() {
        // Tạo container chính cho select
        this.selectElement = document.createElement("div");
        this.selectElement.className = "custom-select";
        this.selectElement.style.width = this.width;

        // Tạo phần hiển thị phần tử đã chọn
        this.selectedElement = document.createElement("div");
        this.selectedElement.className = "custom-select__selected";
        this.selectedElement.innerHTML = `<span>${this.placeholder}</span>`;
        this.renderIcon();

        // Thêm trình nghe sự kiện để bật/tắt dropdown
        this.selectedElement.addEventListener("click", () => this.toggleOptions());

        // Tạo container cho các tùy chọn
        this.optionsContainer = document.createElement("div");
        this.optionsContainer.className = "custom-select__options";

        // Điền các tùy chọn
        this.options.forEach((option) => {
            const optionElement = document.createElement("div");
            optionElement.className = "custom-select__option";
            optionElement.textContent = option.label;
            optionElement.dataset.value = option.value;

            // Thêm trình nghe sự kiện khi chọn một tùy chọn
            optionElement.addEventListener("click", () => this.selectOption(option));
            this.optionsContainer.appendChild(optionElement);
        });

        // Thêm các phần tử vào container chính
        this.selectElement.appendChild(this.selectedElement);
        this.selectElement.appendChild(this.optionsContainer);
        this.container.appendChild(this.selectElement);

        // Thêm trình nghe sự kiện để đóng dropdown khi click ra ngoài
        document.addEventListener("click", (e) => {
            if (!this.selectElement.contains(e.target)) {
                this.closeOptions();
            }
        });
    }

    // Hàm bật dropdown
    toggleOptions() {
        this.selectElement.classList.toggle("open");
    }

    // Hàm đóng dropdown
    closeOptions() {
        this.selectElement.classList.remove("open");
    }

    // Hàm chọn một tùy chọn
    selectOption(option) {
        // Cập nhật giá trị đã chọn
        this.selectedValue = option.value;
        this.selectedElement.innerHTML = `<span>${option.label}</span>`;
        
        // Xóa icon hiện tại
        this.renderIcon();

        // Đóng dropdown
        this.closeOptions();

        // Gọi hàm callback nếu có
        this.onChange(option.value);
    }

    // Hàm lấy giá trị đã chọn
    getSelectedValue() {
        return this.selectedValue;
    }

    // Hàm render icon
    renderIcon() {
        const icon = document.createElement("span");
        icon.className = "custom-select__icon";
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16px" height="16px" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"></path></svg>`;
       
        this.selectedElement.appendChild(icon);
    }
}
