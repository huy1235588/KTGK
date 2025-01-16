document.addEventListener('DOMContentLoaded', () => {
    const skeletonWrappers = document.querySelectorAll('.skeleton-wrapper');

    skeletonWrappers.forEach((wrapper) => {
        const img = wrapper.querySelector('img');
        const realSrc = img.getAttribute('data-src');

        // Lắng nghe sự kiện khi ảnh thật được tải
        const tempImage = new Image();
        tempImage.src = realSrc;

        tempImage.onload = () => {
            img.src = realSrc;
            img.classList.add('loaded'); // Hiển thị ảnh
            wrapper.querySelector('.skeleton').remove(); // Xóa skeleton
        };
    });
});
