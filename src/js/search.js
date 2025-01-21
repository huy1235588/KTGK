const searchbarInput = document.querySelector('.searchbar-input');
const buttonSearchbar = document.querySelector('.button-searchbar');
const searchbarClearBtn = document.querySelector('.searchbar-clear-btn');

// Xoá nội dung trong ô search
searchbarClearBtn.addEventListener('click', () => {
    searchbarInput.value = '';
    searchbarClearBtn.style.opacity = 0;
    
    // Focus vào ô search
    searchbarInput.focus();
});

// Hiển thị gợi ý sản phẩm
searchbarInput.addEventListener('input', async () => {
    const query = searchbarInput.value.trim();

    // khi nhấn nút search
    buttonSearchbar.addEventListener('click', () => {
        window.location.href = `search.php?q=${encodeURIComponent(query)}`;
    });

    if (query.length > 0) {
        searchbarClearBtn.style.opacity = 1;

        // Gọi API để lấy danh sách sản phẩm
        // const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
      
    } else {
        searchbarClearBtn.style.opacity = 0;
    }
});

// Xử lý sự kiện khi focus vào ô search
searchbarInput.addEventListener('keydown', (event) => {
    // Khi nhấn enter
    if (event.key === 'Enter') {
        event.preventDefault();

        // Chưa hover mục nào -> chuyển hướng đến trang tìm kiếm
        if (index === -1) {
            window.location.href = `search.php?q=${encodeURIComponent(searchbarInput.value)}`;
        }
        // Hover mục nào đó -> chuyển hướng tới liên kết của mục đó
        else {
            items[index].click();
        }
    }

    // Khi nhấn tab thì focus vào nút search
    else if (event.key === 'Tab') {
        event.preventDefault();
        dropdown.style.display = 'none';
        buttonSearchbar.focus();
    }
});