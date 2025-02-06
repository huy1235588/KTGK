class PopupInput {
    constructor({ header, label, inputName, inputType, buttonText, placeholder, onSubmit }) {
        this.header = header;
        this.label = label;
        this.inputName = inputName;
        this.inputType = inputType;
        this.buttonText = buttonText;
        this.placeholder = placeholder;
        this.onSubmit = onSubmit;
        this.render();
    }

    // Tạo nút đóng popup
    renderCloseIcon() {
        const closeBtn = document.createElement("button");
        closeBtn.classList.add("close-btn");
        closeBtn.innerHTML = `
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="20px" width="20px" xmlns="http://www.w3.org/2000/svg">
            <path d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z">
            </path>
        </svg>`
        closeBtn.addEventListener("click", () => this.close());
        return closeBtn;
    }

    renderPopupBody() {
        const popupBody = document.createElement("div");
        popupBody.classList.add("form-group");
        popupBody.innerHTML = `
             <label for="${this.inputName}" class="form-label">
                ${this.label} <span class="required">*</span>
            </label>
            <div class="form-control-wrapper">
                <input class="form-control"
                    type="${this.inputType}"
                    id="${this.inputName}"
                    name="${this.inputName}"
                    placeholder="${this.placeholder}">

                <fieldset class="form-control-outline"></fieldset>
            </div>
        `;
        return popupBody;
    }

    // Tạo popup
    render() {
        // Tạo phần tử popup
        this.popupContainer = document.createElement("div");
        this.popupContainer.classList.add("popup-container");
        this.popupContainer.innerHTML = `
            <div class="popup">
                <div class="popup-header">
                    <h2>${this.header}</h2>
                </div>
                <div class="popup-body"></div>
                <div class="popup-footer">
                    <button class="popup-cancel-btn">Cancel</button>
                    <button class="popup-submit-btn">${this.buttonText}</button>
                </div>
            </div>
        `;

        // Thêm popup vào body
        document.body.appendChild(this.popupContainer);

        // Thêm nút đóng
        this.popupContainer.querySelector(".popup-header").appendChild(this.renderCloseIcon());

        // Thêm nội dung popup
        this.popupContainer.querySelector(".popup-body").appendChild(this.renderPopupBody());

        // Lấy các phần tử nút
        this.cancelBtn = this.popupContainer.querySelector(".popup-cancel-btn");
        this.submitBtn = this.popupContainer.querySelector(".popup-submit-btn");

        // Thêm sự kiện
        this.cancelBtn.addEventListener("click", () => this.close());
        this.submitBtn.addEventListener("click", () => this.submit());
        this.popupContainer.addEventListener("click", (event) => {
            if (event.target === this.popupContainer) this.close();
        });
    }

    // Mở và đóng popup
    open() {
        this.popupContainer.style.display = "flex";
    }

    close() {
        this.popupContainer.style.display = "none";
    }

    // Xử lý sự kiện submit
    submit() {
        const inputValue = document.getElementById(this.inputName).value;
        if (inputValue) {
            // Gọi hàm onSubmit
            this.onSubmit(inputValue);
        }
        this.close();
    }
}