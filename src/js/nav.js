const searchInput = document.querySelector('.search-input');
const buttonSearch = document.querySelector('.button-search');
const buttonClear = document.querySelector('.button-clear');
const dropdown = document.querySelector('.search-dropdown');

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Hàm fetch API
async function fetchSearchResults() {
    const query = searchInput.value.trim();

    if (query.length > 0) {
        buttonClear.style.opacity = 1;
        try {
            // Gọi API để lấy danh sách sản phẩm
            const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);

            // Chuyển đổi response sang text (HTML)
            const html = await response.text();

            // Hiển thị danh sách sản phẩm
            dropdown.innerHTML = html;

            // Hiển thị dropdown
            dropdown.style.display = 'block';
        } catch (error) {
            console.error(error);
        }
    } else {
        buttonClear.style.opacity = 0;
        dropdown.style.display = 'none';
    }

}

// Xoá nội dung trong ô search
buttonClear.addEventListener('click', () => {
    searchInput.value = '';
    dropdown.style.display = 'none';

    // Focus vào ô search
    searchInput.focus();
});

// Hiển thị gợi ý sản phẩm
searchInput.addEventListener('input', debounce(fetchSearchResults, 300));

// khi nhấn nút search
buttonSearch.addEventListener('click', () => {
    window.location.href = `search.php?q=${encodeURIComponent(query)}`;
});

// Ẩn dropdown khi click ra ngoài
document.addEventListener('click', (event) => {
    if (!event.target.closest('.search-container')) {
        dropdown.style.display = 'none';
    }
});

// Hiện lại dropdown khi click vào ô search
searchInput.addEventListener('click', () => {
    if (searchInput.value.trim().length > 0) {
        dropdown.style.display = 'block';
    }
});

// Hover vào dropdown item khi nhấn nút lên xuống
searchInput.addEventListener('keydown', (event) => {
    const items = dropdown.querySelectorAll('.search-result-item .search-result-link');

    // Tìm index của item đang có class hover
    let index = Array.from(items).findIndex(item => item.classList.contains('hover'));

    // Khi nhấn enter
    if (event.key === 'Enter') {
        event.preventDefault();

        // Chưa hover mục nào -> chuyển hướng đến trang tìm kiếm
        if (index === -1) {
            window.location.href = `search.php?q=${encodeURIComponent(searchInput.value)}`;
        }
        // Hover mục nào đó -> chuyển hướng tới liên kết của mục đó
        else {
            items[index].click();
        }
    }

    // Đặt focus vào item đầu tiên nếu chưa hover
    else if (index === -1 && event.key === 'ArrowDown') {
        event.preventDefault();
        items[0].classList.add('hover');
        return;
    }

    // Xử lý sự kiện khi nhấn nút lên xuống
    if (event.key === 'ArrowDown') {
        event.preventDefault();
        items[index].classList.remove('hover');
        index = (index + 1) % items.length;
        items[index].classList.add('hover');
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        items[index].classList.remove('hover');
        index = (index - 1 + items.length) % items.length;
        items[index].classList.add('hover');
    }

    // Khi nhấn tab thì focus vào nút search
    else if (event.key === 'Tab') {
        event.preventDefault();
        dropdown.style.display = 'none';
        buttonSearch.focus();
    }
});

// Cập nhật index khi hover vào item
dropdown.addEventListener('mouseover', (event) => {
    const items = dropdown.querySelectorAll('.search-result-item .search-result-link');

    if (!event.target.classList.contains('search-result-link')) {
        return;
    }

    // Loại bỏ class hover khỏi tất cả các item
    items.forEach(item => item.classList.remove('hover'));

    // Thêm class hover vào item được hover
    event.target.classList.add('hover');
});

// Chọn sản phẩm từ danh sách
function selectProduct(productName) {
    searchInput.value = productName;
    dropdown.style.display = 'none';
}

// Hiển thị dropdown menu
document.querySelectorAll(".menu .username").forEach(element => {
    const dropdown = element.nextElementSibling;

    // Hàm để đóng dropdown khi click ra ngoài
    const closeDropdownOnClickOutside = (e) => {
        if (!dropdown.contains(e.target) && e.target !== element) {
            dropdown.classList.add("hs-hidden");
            dropdown.classList.remove("hs-visible");
            // Xóa listener khi dropdown bị đóng
            window.removeEventListener('click', closeDropdownOnClickOutside);
        }
    };

    element.addEventListener('click', () => {
        // Kiểm tra trạng thái dropdown
        if (dropdown.classList.contains("hs-visible")) {
            // Đóng dropdown nếu đang mở
            dropdown.classList.add("hs-hidden");
            dropdown.classList.remove("hs-visible");
            window.removeEventListener('click', closeDropdownOnClickOutside);
        } else {
            // Mở dropdown và thêm listener để đóng khi nhấn ra ngoài
            dropdown.classList.remove("hs-hidden");
            dropdown.classList.add("hs-visible");

            setTimeout(() => {
                window.addEventListener('click', closeDropdownOnClickOutside);
            }, 0);
        }
    });
});