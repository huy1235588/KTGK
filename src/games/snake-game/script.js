/* game.js */
/*
  Trò chơi Snake với tính năng tăng tốc khi nhấn giữ phím space.
  Giao diện sử dụng tiếng Anh, nhưng các comment bằng tiếng Việt.
*/

//==========================
// Khai báo các biến cài đặt và khởi tạo từ localStorage nếu có
//==========================
let defaultSpeed = 4;      // Tốc độ mặc định = 4
let defaultGridSize = 20;  // Kích thước grid mặc định = 20
let defaultWalls = false;  // Mặc định không bật tường

// Lấy cài đặt từ localStorage nếu tồn tại
let speedSetting = localStorage.getItem('speedSetting') ? parseInt(localStorage.getItem('speedSetting')) : defaultSpeed;
let grid = localStorage.getItem('gridSize') ? parseInt(localStorage.getItem('gridSize')) : defaultGridSize;
let wallsEnabled = localStorage.getItem('wallsEnabled') ? (localStorage.getItem('wallsEnabled') === 'true') : defaultWalls;

// Tính toán frameInterval dựa trên tốc độ (càng nhanh: frameInterval càng nhỏ)
let frameInterval = 11 - speedSetting;

// Biến để kiểm tra chế độ tăng tốc (boost)
let isBoosting = false;

//==========================
// Khai báo các biến trò chơi
//==========================
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

let count = 0;        // Biến đếm frame
let score = 0;        // Điểm số
let gameOver = false; // Cờ báo game kết thúc

// Đối tượng snake (rắn)
let snake = {
    x: grid * 5,   // Tọa độ bắt đầu theo x
    y: grid * 5,   // Tọa độ bắt đầu theo y
    dx: grid,      // Tốc độ di chuyển theo x (mặc định sang phải)
    dy: 0,         // Tốc độ di chuyển theo y
    cells: [],     // Mảng chứa các ô của rắn
    maxCells: 4    // Độ dài ban đầu của rắn
};

// Đối tượng apple (mồi)
let apple = {
    x: grid * 10,
    y: grid * 10
};

//==========================
// Hàm lấy số nguyên ngẫu nhiên trong khoảng [min, max)
//==========================
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

//==========================
// Hàm reset lại trạng thái của trò chơi
//==========================
function resetGame() {
    snake.x = grid * 5;
    snake.y = grid * 5;
    snake.cells = [];
    snake.maxCells = 4;
    snake.dx = grid;
    snake.dy = 0;
    apple.x = grid * 10;
    apple.y = grid * 10;
    score = 0;
    document.getElementById('score').innerText = score;
    document.getElementById('gameOverText').style.display = 'none';
    document.getElementById('restartBtn').style.display = 'none';
    gameOver = false;
    count = 0;
    // Bắt đầu lại vòng lặp game
    requestAnimationFrame(loop);
}

//==========================
// Hàm xử lý thay đổi hướng di chuyển của rắn
//==========================
function changeDirection(direction) {
    // Kiểm tra để không cho rắn quay ngược lại
    if (direction === 'left' && snake.dx === 0) {
        snake.dx = -grid;
        snake.dy = 0;
    } else if (direction === 'up' && snake.dy === 0) {
        snake.dy = -grid;
        snake.dx = 0;
    } else if (direction === 'right' && snake.dx === 0) {
        snake.dx = grid;
        snake.dy = 0;
    } else if (direction === 'down' && snake.dy === 0) {
        snake.dy = grid;
        snake.dx = 0;
    }
}

//==========================
// Hàm vòng lặp game chính
//==========================
function loop() {
    // Điều chỉnh frameInterval khi tăng tốc (boost)
    let effectiveFrameInterval = isBoosting ? Math.max(1, Math.floor(frameInterval / 2)) : frameInterval;

    // Tăng biến đếm, chỉ chạy logic game khi đạt effectiveFrameInterval
    if (++count < effectiveFrameInterval) {
        requestAnimationFrame(loop);
        return;
    }
    count = 0;

    // Xóa canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Cập nhật vị trí của rắn
    snake.x += snake.dx;
    snake.y += snake.dy;

    // Kiểm tra va chạm với tường hoặc thực hiện wrap nếu tường không bật
    if (wallsEnabled) {
        // Nếu bật tường, va chạm với tường kết thúc game
        if (snake.x < 0 || snake.x >= canvas.width || snake.y < 0 || snake.y >= canvas.height) {
            gameOver = true;
            document.getElementById('gameOverText').style.display = 'block';
            document.getElementById('restartBtn').style.display = 'inline-block';
            return;
        }
    } else {
        // Nếu không bật tường, thực hiện wrap quanh màn hình
        if (snake.x < 0) {
            snake.x = canvas.width - grid;
        } else if (snake.x >= canvas.width) {
            snake.x = 0;
        }
        if (snake.y < 0) {
            snake.y = canvas.height - grid;
        } else if (snake.y >= canvas.height) {
            snake.y = 0;
        }
    }

    // Thêm vị trí đầu mới vào mảng cells của rắn
    snake.cells.unshift({ x: snake.x, y: snake.y });
    // Giữ độ dài của rắn
    if (snake.cells.length > snake.maxCells) {
        snake.cells.pop();
    }

    // Vẽ quả táo
    ctx.fillStyle = 'red';
    ctx.fillRect(apple.x, apple.y, grid - 1, grid - 1);

    // Vẽ rắn và kiểm tra va chạm với thân rắn
    ctx.fillStyle = 'lime';
    for (let i = 0; i < snake.cells.length; i++) {
        ctx.fillRect(snake.cells[i].x, snake.cells[i].y, grid - 1, grid - 1);
        // Kiểm tra va chạm giữa đầu rắn và thân rắn
        if (i !== 0 && snake.cells[i].x === snake.x && snake.cells[i].y === snake.y) {
            gameOver = true;
            document.getElementById('gameOverText').style.display = 'block';
            document.getElementById('restartBtn').style.display = 'inline-block';
            return;
        }
    }

    // Kiểm tra nếu rắn ăn được táo
    if (snake.x === apple.x && snake.y === apple.y) {
        snake.maxCells++; // Tăng độ dài của rắn
        score++;          // Tăng điểm
        document.getElementById('score').innerText = score;
        // Đặt lại vị trí của táo một cách ngẫu nhiên
        apple.x = getRandomInt(0, canvas.width / grid) * grid;
        apple.y = getRandomInt(0, canvas.height / grid) * grid;
    }

    // Nếu game chưa kết thúc, tiếp tục vòng lặp
    if (!gameOver) {
        requestAnimationFrame(loop);
    }
}

// Bắt đầu vòng lặp game ban đầu
requestAnimationFrame(loop);

//==========================
// Xử lý sự kiện bàn phím (mũi tên, WSDA và tăng tốc khi giữ space)
//==========================
document.addEventListener('keydown', function (e) {
    // Sử dụng các phím mũi tên
    if (e.key === 'ArrowLeft') {
        changeDirection('left');
    } else if (e.key === 'ArrowUp') {
        changeDirection('up');
    } else if (e.key === 'ArrowRight') {
        changeDirection('right');
    } else if (e.key === 'ArrowDown') {
        changeDirection('down');
    }
    // Sử dụng phím WSDA
    else if (e.key === 'a' || e.key === 'A') {
        changeDirection('left');
    } else if (e.key === 'w' || e.key === 'W') {
        changeDirection('up');
    } else if (e.key === 'd' || e.key === 'D') {
        changeDirection('right');
    } else if (e.key === 's' || e.key === 'S') {
        changeDirection('down');
    }
    // Nếu nhấn giữ phím space, bật chế độ tăng tốc (boost)
    else if (e.key === ' ' || e.key === 'Spacebar' || e.code === 'Space') {
        isBoosting = true;
    }
});

// Lắng nghe sự kiện nhả phím để tắt chế độ tăng tốc
document.addEventListener('keyup', function (e) {
    if (e.key === ' ' || e.key === 'Spacebar' || e.code === 'Space') {
        isBoosting = false;
    }
});

//==========================
// Xử lý sự kiện cho các nút WSDA
//==========================
document.getElementById('btn-w').addEventListener('click', function () {
    changeDirection('up');
});
document.getElementById('btn-a').addEventListener('click', function () {
    changeDirection('left');
});
document.getElementById('btn-s').addEventListener('click', function () {
    changeDirection('down');
});
document.getElementById('btn-d').addEventListener('click', function () {
    changeDirection('right');
});

//==========================
// Xử lý nút Restart
//==========================
document.getElementById('restartBtn').addEventListener('click', function () {
    resetGame();
});

//==========================
// Xử lý nút Settings và modal popup
//==========================
const settingsBtn = document.getElementById('settingsBtn');
const settingsModal = document.getElementById('settingsModal');
const modalClose = document.getElementById('modalClose');
const settingsForm = document.getElementById('settingsForm');

// Khi nhấn nút Settings, hiển thị modal popup
settingsBtn.addEventListener('click', function () {
    // Đưa các giá trị hiện tại vào form
    document.getElementById('speed').value = speedSetting;
    document.getElementById('gridSize').value = grid;
    document.getElementById('wallsEnabled').checked = wallsEnabled;
    settingsModal.style.display = 'block';
});

// Khi nhấn dấu "x" để đóng modal
modalClose.addEventListener('click', function () {
    settingsModal.style.display = 'none';
});

// Khi nhấn bên ngoài modal cũng đóng modal
window.addEventListener('click', function (event) {
    if (event.target == settingsModal) {
        settingsModal.style.display = 'none';
    }
});

// Xử lý form cài đặt khi nhấn nút "Save Settings"
settingsForm.addEventListener('submit', function (e) {
    e.preventDefault();
    // Lấy giá trị từ form
    speedSetting = parseInt(document.getElementById('speed').value);
    grid = parseInt(document.getElementById('gridSize').value);
    wallsEnabled = document.getElementById('wallsEnabled').checked;
    // Tính lại frameInterval dựa trên tốc độ mới
    frameInterval = 11 - speedSetting;
    // Lưu cài đặt vào localStorage
    localStorage.setItem('speedSetting', speedSetting);
    localStorage.setItem('gridSize', grid);
    localStorage.setItem('wallsEnabled', wallsEnabled);
    // Đóng modal
    settingsModal.style.display = 'none';
    // Reset game để áp dụng cài đặt mới
    resetGame();
});
