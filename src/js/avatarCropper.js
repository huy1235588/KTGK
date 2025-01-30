class AvatarCropper {
    constructor() {
        this.cropBox = document.getElementById("cropBox");
        this.avatarUploader = document.getElementById("avatarUploader");
        this.imageToCrop = document.getElementById('imageToCrop');
        this.avatarImg = document.getElementById("avatarImg");
        this.deleteAvatarBtn = document.getElementById("deleteAvatarBtn");
        this.cropBtnSave = document.getElementById("cropBtnSave");
        this.cropBtnCancel = document.getElementById("cropBtnCancel");
        this.cropBtnClose = document.getElementById("cropBtnClose");

        this.cropper = null;
        this.cropAble = false;

        this.initEventListeners();
    }

    initEventListeners() {
        this.avatarUploader.addEventListener("change", this.handleFileUpload.bind(this));
        this.cropBtnSave.addEventListener("click", this.handleSave.bind(this));
        this.cropBtnCancel.addEventListener("click", this.handleCancel.bind(this));
        this.cropBtnClose.addEventListener("click", this.handleCancel.bind(this));
        this.deleteAvatarBtn.addEventListener("click", this.handleDeleteAvatar.bind(this));
    }

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageToCrop.src = e.target.result;
                this.cropBox.style.display = "block";

                this.imageToCrop.onload = () => {
                    if (this.cropper) {
                        this.cropper.destroy();
                    }

                    this.cropper = new Cropper(this.imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        zoomable: false,
                        minCropBoxWidth: 100,
                        minCropBoxHeight: 100,
                        ready: () => {
                            this.cropAble = true;
                            this.cropper.setCropBoxData({
                                height: this.imageToCrop.height
                            });
                        },
                    });
                };
            };
            reader.readAsDataURL(file);
        }
    }

    handleSave() {
        if (!this.cropAble) {
            return;
        }

        const croppedCanvas = this.cropper.getCroppedCanvas();
        const roundedCanvas = this.getRoundedCanvas(croppedCanvas);
        const dataUrl = roundedCanvas.toDataURL('image/png');

        this.avatarImg.src = dataUrl;
        this.cropBox.style.display = "none";
        this.cropper.destroy();
    }

    handleCancel() {
        this.cropBox.style.display = "none";
        this.imageToCrop.value = "";
        this.imageToCrop.removeAttribute('src');

        if (this.cropper) {
            this.cropper.destroy();
        }
    }

    handleDeleteAvatar() {
        if (this.avatarImg.src !== "/admin/assets/img/avatar/img1.jpg") {
            this.avatarImg.src = "/admin/assets/img/avatar/img1.jpg";
        }
    }

    getRoundedCanvas(sourceCanvas) {
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
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new AvatarCropper();
});