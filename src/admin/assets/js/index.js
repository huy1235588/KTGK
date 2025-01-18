/*******************************************
* 
*          Search input
* 
*******************************************/
const searchClear = document.querySelector('.search-clear');
const inputSearch = document.querySelector('.input-search');


inputSearch.addEventListener('input', () => {
    searchClear.style.display = "flex";

    searchClear.addEventListener('click', () => {
        inputSearch.value = "";
        inputSearch.focus();
        searchClear.style.display = "none";
    });
});

/*******************************************
* 
*          Tooltip
* 
*******************************************/
// Get all elements with data-toggle="tooltip"
const tooltipElements = document.querySelectorAll('[data-toggle="tooltip"]');

// Hàm tạo tooltip element
function createTooltip(text, dataPlacement, x, y) {
    const tooltip = document.createElement('div');
    tooltip.classList.add("tooltip");

    const arrow = document.createElement('p');
    arrow.classList.add('tooltip-arrow');
    const inner = document.createElement('p');
    inner.classList.add('tooltip-inner');
    inner.textContent = text;

    tooltip.appendChild(arrow);
    tooltip.appendChild(inner);

    // Set vị trí tooltip
    tooltip.style.left = `${x + 5}px`;
    tooltip.style.top = `${y}px`;
    document.body.appendChild(tooltip);

    // Thêm class arrow
    tooltip.classList.add(`bs-tooltip-${dataPlacement}`);
    arrow.style.top = `${inner.offsetHeight / 2 - arrow.offsetHeight / 2}px`;

    return tooltip;
}

tooltipElements.forEach(element => {
    let tooltip;

    // Show tooltip khi mouseover
    element.addEventListener('mouseover', () => {
        // Set văn bản tooltip từ data-original-title
        const title = element.getAttribute('data-original-title');
        const rect = element.getBoundingClientRect();
        const dataPlacement = element.getAttribute("data-placement");

        tooltip = createTooltip(title, dataPlacement, rect.right, rect.top);
    });

    // Remove tooltip khi mouseout
    element.addEventListener('mouseout', () => {
        if (tooltip) {
            tooltip.remove();
            tooltip = null;
        }
    });
});

/*******************************************
* 
*          Sidebar Submenu
* 
*******************************************/
function toggleSidebarSubMenu(element, sidebarLink, e) {
    // Xóa toàn bộ lớp 'active' ngoài element đang nhấn
    $(sidebarLink).not($(e.currentTarget)).removeClass("active");

    // Xóa lớp 'active'
    if (element.classList.contains("active")) {
        element.classList.remove("active");
    }

    // Thêm lớp 'active'
    else {
        element.classList.add("active");
    }
}

$(document).ready(function () {
    // Gán class 'show' cho submenu đầu tiên khi trang tải (tùy chọn)
    $('.sidebar-submenu-list.show').slideDown(0);

    // Event click để toggle các submenu
    $('.sidebar-link').on('click', function (e) {
        // Submenu level 3
        if ($(this).parent().parent().parent().hasClass("sidebar-submenu-item-has-menu")) {
            if ($(e.currentTarget).siblings('.sidebar-submenu-list').hasClass('show')) {
                // Nếu submenu đang có class 'show', thực hiện đóng nó
                $(e.currentTarget).siblings('.sidebar-submenu-list').slideUp(200, function () {
                    $(e.currentTarget).siblings('.sidebar-submenu-list').removeClass('show'); // Xóa class 'show' khi đóng
                });
            } else {
                // Nếu submenu không có class 'show', mở nó và thêm class 'show'
                $(e.currentTarget).siblings('.sidebar-submenu-list').addClass('show').slideDown(200);
            }

            // Đóng tất cả các submenu khác khi một submenu được mở
            $(".sidebar-submenu-list.show > .sidebar-submenu-item-has-menu .sidebar-submenu-list .sidebar-submenu-item-has-menu .sidebar-submenu-list").not($(e.currentTarget).siblings('.sidebar-submenu-list')).slideUp(200, function () {
                $(this).removeClass('show'); // Xóa class 'show' cho các submenu khác
            });

            toggleSidebarSubMenu($(this)[0], ".sidebar-item-has-menu > .sidebar-submenu-list > .sidebar-submenu-item-has-menu > .sidebar-submenu-list > .sidebar-submenu-item-has-menu > .sidebar-submenu-item-link", e);
        }

        // Submenu level 2
        else if ($(this).parent().hasClass("sidebar-submenu-item-has-menu")) {
            if ($(e.currentTarget).siblings('.sidebar-submenu-list').hasClass('show')) {
                // Nếu submenu đang có class 'show', thực hiện đóng nó
                $(e.currentTarget).siblings('.sidebar-submenu-list').slideUp(200, function () {
                    $(e.currentTarget).siblings('.sidebar-submenu-list').removeClass('show'); // Xóa class 'show' khi đóng
                });
            } else {
                // Nếu submenu không có class 'show', mở nó và thêm class 'show'
                $(e.currentTarget).siblings('.sidebar-submenu-list').addClass('show').slideDown(200);
            }

            // Đóng tất cả các submenu khác khi một submenu được mở
            $(".sidebar-submenu-list.show > .sidebar-submenu-item-has-menu .sidebar-submenu-list").not($(e.currentTarget).siblings('.sidebar-submenu-list')).slideUp(200, function () {
                $(this).removeClass('show'); // Xóa class 'show' cho các submenu khác
            });

            toggleSidebarSubMenu($(this)[0], ".sidebar-item-has-menu > .sidebar-submenu-list > .sidebar-submenu-item-has-menu > .sidebar-submenu-item-link", e);
        }

        // Submenu level 1
        else {
            if ($(e.currentTarget).siblings('.sidebar-submenu-list').hasClass('show')) {
                // Nếu submenu đang có class 'show', thực hiện đóng nó
                $(e.currentTarget).siblings('.sidebar-submenu-list').slideUp(200, function () {
                    $(e.currentTarget).siblings('.sidebar-submenu-list').removeClass('show'); // Xóa class 'show' khi đóng
                });
            } else {
                // Nếu submenu không có class 'show', mở nó và thêm class 'show'
                $(e.currentTarget).siblings('.sidebar-submenu-list').addClass('show').slideDown(200);
            }

            // Đóng tất cả các submenu khác khi một submenu được mở
            $('.sidebar-submenu-list').not($(e.currentTarget).siblings('.sidebar-submenu-list')).slideUp(200, function () {
                $(this).removeClass('show'); // Xóa class 'show' cho các submenu khác
            });

            toggleSidebarSubMenu($(this)[0], ".sidebar-item-link", e);
        }
    });
});

/*******************************************
* 
*          Toggle aside
* 
*******************************************/
const body = document.getElementsByTagName('body')[0],
    isMini = window.localStorage.getItem('hs-navbar-vertical-aside-mini') === null ? false : window.localStorage.getItem('hs-navbar-vertical-aside-mini');

if (isMini) {
    body.classList.add('navbar-vertical-aside-mini-mode')
}

// Định nghĩa các hàm sự kiện
function showMenu(event) {
    event.currentTarget.lastElementChild.style.display = "block";
}

function hideMenu(event) {
    event.currentTarget.lastElementChild.style.display = "none";
}

// Hàm toggle aside
function toggleAside() {
    // Thu nhỏ aside
    if (body.classList.contains('navbar-vertical-aside-mini-mode')) {
        body.classList.remove('navbar-vertical-aside-mini-mode');

        if (document.querySelector(".sidebar-item-has-menu > .sidebar-submenu-list.show")) {
            document.querySelector(".sidebar-item-has-menu > .sidebar-submenu-list.show").style.display = "block";
        }
        if (document.querySelector(".sidebar-list > .sidebar-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show")) {
            document.querySelector(".sidebar-list > .sidebar-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show").style.display = "block";
        }
        if (document.querySelector(".sidebar-list > .sidebar-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show")) {
            document.querySelector(".sidebar-list > .sidebar-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show > .sidebar-submenu-item-has-menu > .sidebar-submenu-list.show").style.display = "block";
        }

        document.querySelectorAll(".nav-tabs > li").forEach(element => {
            element.firstElementChild.style.pointerEvents = "auto";
        });

        // Xóa sự kiện hiện menu
        document.querySelectorAll(".sidebar-item-has-menu").forEach(element => {
            element.removeEventListener('mouseenter', showMenu);
            element.removeEventListener('mouseleave', hideMenu);
        });

    }

    // Phóng to aside
    else {
        body.classList.add('navbar-vertical-aside-mini-mode');

        document.querySelectorAll(".sidebar-submenu-list").forEach(element => {
            element.style.display = "none";
        });

        document.querySelectorAll(".nav-tabs > li").forEach(element => {
            element.firstElementChild.style.pointerEvents = "none";
            element.style.cursor = "pointer";
        });

        // Thêm sự kiện hiện menu
        document.querySelectorAll(".sidebar-item-has-menu").forEach(element => {
            element.addEventListener('mouseenter', showMenu);
            element.addEventListener('mouseleave', hideMenu);
        });

        // Set vị trí hiện menu
        document.querySelectorAll('.sidebar-submenu-list').forEach(element => {
            element.style.top = `${element.previousElementSibling.offsetTop}px`;
        })
    }
}

// Hàm ẩn menu của mobile
const closeMenuOnClickOutside = (e) => {
    if (!document.querySelector('.navbar-vertical-aside').contains(e.target) && e.target !== btnCollapse) {
        body.classList.add("navbar-vertical-aside-closed-mode");
        // Xóa listener khi menu bị đóng
        window.removeEventListener('click', closeMenuOnClickOutside);
    }
};

// Thu nhỏ aside
var btnCollapse = document.querySelector('.toggle-vertical-aside');
btnCollapse.addEventListener('click', () => {
    // Kiểm tra có phải màn hình mobile
    if (checkMobileScreen()) {
        body.classList.remove("navbar-vertical-aside-closed-mode");

        if (!(body.classList.contains("navbar-vertical-aside-closed-mode"))) {
            // setTimeout để trì hoãn việc thêm listener
            setTimeout(() => {
                window.addEventListener('click', closeMenuOnClickOutside);
            }, 100);
        }
    }

    else {
        toggleAside();
    }
});

/*******************************************
* 
*          Moblile
* 
*******************************************/
// Hàm kiểm tra mobile screen
function checkMobileScreen() {
    return window.innerWidth <= 1200;
}

function toggleNavbarMode() {
    if (checkMobileScreen()) {
        $(body).addClass("navbar-vertical-aside-closed-mode");
        $(body).removeClass("navbar-vertical-aside-mini-mode");
    } else {
        $(body).removeClass("navbar-vertical-aside-closed-mode");
    }
}

// Gọi hàm khi trang load
$(document).ready(function () {
    toggleNavbarMode();
});

// Gọi hàm khi thay đổi kích thước cửa sổ
$(window).resize(function () {
    toggleNavbarMode();
    window.removeEventListener('click', closeMenuOnClickOutside);
});

/*******************************************
* 
*          Navbar-dropdown
* 
*******************************************/
document.querySelectorAll(".navbar-content-right .navbar-button").forEach(element => {
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

// Navbar dropdown submenu
document.querySelectorAll(".navbar-content-right .navbar-dropdown-submenu-btn").forEach(element => {
    const dropdown = element.nextElementSibling;

    element.parentElement.addEventListener('mouseover', () => {
        // Kiểm tra trạng thái dropdown
        if (dropdown.classList.contains("hs-visible")) {
            // Đóng dropdown nếu đang mở
            dropdown.classList.add("hs-hidden");
            dropdown.classList.remove("hs-visible");
            // window.removeEventListener('click', closeDropdownOnClickOutside);
        } else {
            // Mở dropdown và thêm listener để đóng khi nhấn ra ngoài
            dropdown.classList.remove("hs-hidden");
            dropdown.classList.add("hs-visible");
        }
    });

    element.parentElement.addEventListener('mouseout', () => {
        // Kiểm tra trạng thái dropdown
        if (dropdown.classList.contains("hs-hidden")) {
            // Đóng dropdown nếu đang mở
            dropdown.classList.add("hs-visible");
            dropdown.classList.remove("hs-hidden");
            // window.removeEventListener('click', closeDropdownOnClickOutside);
        } else {
            // Mở dropdown và thêm listener để đóng khi nhấn ra ngoài
            dropdown.classList.remove("hs-visible");
            dropdown.classList.add("hs-hidden");
        }
    });
});