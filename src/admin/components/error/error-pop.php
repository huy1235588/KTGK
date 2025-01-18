<style>
    .error-pop {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        color: #fff;
        width: 40%;
        max-width: 50%;
        max-height: 30%;
        transform: translateX(-50%) translateY(-50%);
        border-radius: 10px;
        background-color: #383838;
        z-index: 100;
    }

    /* Header */
    .error-header {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 60px;
    }

    /* Close button */
    .error-close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ffffff1a;
    }

    .error-close-btn:hover {
        background-color: #ffffff28;
    }

    .error-detail {
        padding: 5px 30px 10px;
    }

    .error-btn {
        display: flex;
        justify-content: flex-end;
        padding: 16px;
    }

    .error-cancel-btn {
        padding: 12px 40px;
        margin-right: 5px;
        border-radius: 8px;
        background-color: #0866ff;
    }

    .error-cancel-btn:hover {
        background-color: #2075ff;
    }
</style>

<div class="error-pop">
    <!-- Header -->
    <h2 class="error-header"> </h2>

    <!-- Closer button -->
    <button class="error-close-btn">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
            <path d="m289.94 256 95-95A24 24 0 0 0 351 127l-95 95-95-95a24 24 0 0 0-34 34l95 95-95 95a24 24 0 1 0 34 34l95-95 95 95a24 24 0 0 0 34-34z"></path>
        </svg>
    </button>

    <p class="error-detail"> </p>

    <!-- Confirm button -->
    <div class="error-btn">
        <button type="button" class="error-cancel-btn">
            Close
        </button>
    </div>
</div>

<script>
    // Xử lý nút cancel
    document.querySelector(".error-close-btn").addEventListener('click', () => {
        document.querySelector(".error-pop").style.display = "none";
    });

    // Xử lý nút đóng
    document.querySelector(".error-cancel-btn").addEventListener('click', () => {
        document.querySelector(".error-pop").style.display = "none";
    });

    // gán nội dung header lỗi
    document.getElementsByClassName("error-header")[0].textContent = errorHeader;
    // gán nội dung lỗi
    document.getElementsByClassName("error-detail")[0].textContent = errorDetail;
</script>