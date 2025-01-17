document.addEventListener('DOMContentLoaded', () => {
    const productItems = document.querySelectorAll('.product-item');
    const hoverElement = document.querySelector('.product-hover');
    const hoverArrowLeft = hoverElement.querySelector('.arrow-left');
    const hoverArrowRight = hoverElement.querySelector('.arrow-right');
    const hoverContentContainer = hoverElement.querySelector('.product-hover-content');

    // Timeout để hover không bị nhấp nháy
    let hoverTimeout;

    // Hiển thị hover
    const showHover = (item) => {
        clearTimeout(hoverTimeout);

        // Nếu hover đang hiển thị thì không hiển thị nữa
        hoverTimeout = setTimeout(() => {
            hoverElement.classList.add('active');

            // Tính vị trí hiển thị
            const itemRect = item.getBoundingClientRect();
            const hoverElementRect = hoverElement.getBoundingClientRect();
            const top = itemRect.top + window.scrollY;
            let left = itemRect.right + window.scrollX + 10;

            // Kiểm tra vị trí hiển thị
            if (left + hoverElementRect.width > window.innerWidth) {
                left = itemRect.left - hoverElementRect.width - 10;
                hoverArrowLeft.style.display = 'none';
                hoverArrowRight.style.display = 'block';
            } else {
                hoverArrowLeft.style.display = 'block';
                hoverArrowRight.style.display = 'none';
            }

            // Cập nhật vị trí
            hoverElement.style.top = `${top}px`;
            hoverElement.style.left = `${left}px`;

            // Hiển thị nội dung
            const id = item.getAttribute('data-id');
            const content = hoverContentContainer.querySelector(`#hover-product-${id}`);

            if (content) {
                content.style.display = 'block';
            } else {
                // Fetch nội dung nếu chưa có
                fetch(`/api/product_hover.php?id=${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok.');
                        return response.text();
                    })
                    .then(data => {
                        hoverContentContainer.innerHTML += data;
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        }, 100);
    };

    // Ẩn hover
    const hideHover = () => {
        clearTimeout(hoverTimeout);
        hoverElement.classList.remove('active');
        for (const content of hoverContentContainer.children) {
            content.style.display = 'none';
        }
    };

    // Thêm sự kiện vào từng item
    productItems.forEach(item => {
        item.addEventListener('mouseenter', () => showHover(item));
        item.addEventListener('mouseleave', hideHover);
    });
});
