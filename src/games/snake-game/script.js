/* game.js */
/*
  Trò chơi Snake với tính năng tăng tốc khi nhấn giữ boost key, chế độ Multiplayer,
  và hiển thị popup với thông báo người chơi thắng (hoặc Game Over trong Singleplayer).
  
  Trong chế độ Multiplayer:
    - Player1 sử dụng các phím mũi tên để điều khiển, boost với phím "Enter".
    - Player2 sử dụng các phím WSAD để điều khiển, boost với phím "Space".
  
  Nếu Multiplayer tắt, cả hai bộ điều khiển đều điều khiển snake1, boost bằng Space.
  
  Các comment được viết bằng tiếng Việt.
*/

//==========================
// Khai báo các biến cài đặt và khởi tạo từ localStorage nếu có
//==========================
let defaultSpeed = 4;      // Tốc độ mặc định = 4
let defaultGridSize = 20;  // Kích thước grid mặc định = 20
let defaultWalls = false;  // Mặc định không bật tường
let defaultMultiplayer = false; // Mặc định chế độ multiplayer tắt

// Lấy cài đặt từ localStorage nếu tồn tại
let speedSetting = localStorage.getItem('speedSetting') ? parseInt(localStorage.getItem('speedSetting')) : defaultSpeed;
let grid = localStorage.getItem('gridSize') ? parseInt(localStorage.getItem('gridSize')) : defaultGridSize;
let wallsEnabled = localStorage.getItem('wallsEnabled') ? (localStorage.getItem('wallsEnabled') === 'true') : defaultWalls;
let multiplayerMode = localStorage.getItem('multiplayerMode') ? (localStorage.getItem('multiplayerMode') === 'true') : defaultMultiplayer;

// Tính toán frameInterval dựa trên tốc độ (càng nhanh: frameInterval càng nhỏ)
let frameInterval = 11 - speedSetting;

//==========================
// Khai báo biến boost cho từng người chơi
//==========================
let isBoosting1 = false; // Boost cho player1 (arrow keys)
let isBoosting2 = false; // Boost cho player2 (WSAD) trong multiplayer

//==========================
// Khai báo các biến trò chơi
//==========================
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

// Các biến đếm riêng cho từng snake
let count1 = 0;
let count2 = 0;

let gameOver = false; // Cờ báo kết thúc game

// Điểm số cho player1 và player2 (nếu multiplayer)
let score1 = 0;
let score2 = 0;

// Biến lưu thông điệp thắng/thua
let winnerMessage = "";

// Đối tượng snake cho player1
let snake1 = {
    x: grid * 5,   // Tọa độ bắt đầu theo x
    y: grid * 5,   // Tọa độ bắt đầu theo y
    dx: grid,      // Tốc độ di chuyển theo x (mặc định sang phải)
    dy: 0,         // Tốc độ di chuyển theo y
    cells: [],     // Mảng chứa các ô của snake
    maxCells: 4    // Độ dài ban đầu của snake
};

// Nếu chế độ multiplayer bật, khởi tạo snake2
let snake2 = null;
if (multiplayerMode) {
    snake2 = {
        x: grid * 15,  // Tọa độ bắt đầu cho player2
        y: grid * 15,
        dx: 0,         // Ban đầu đứng yên
        dy: grid,      // Di chuyển xuống
        cells: [],
        maxCells: 4
    };
}

// Đối tượng apple (mồi), dùng chung cho cả hai snake
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
    gameOver = false;
    count1 = 0;
    count2 = 0;
    score1 = 0;
    score2 = 0;
    winnerMessage = "";

    // Reset snake1
    snake1.x = grid * 5;
    snake1.y = grid * 5;
    snake1.dx = grid;
    snake1.dy = 0;
    snake1.cells = [];
    snake1.maxCells = 4;

    // Reset snake2 nếu có chế độ multiplayer
    if (multiplayerMode && snake2) {
        snake2.x = grid * 15;
        snake2.y = grid * 15;
        snake2.dx = 0;
        snake2.dy = grid;
        snake2.cells = [];
        snake2.maxCells = 4;
    }

    // Đặt lại vị trí của táo một cách ngẫu nhiên
    apple.x = getRandomInt(0, canvas.width / grid) * grid;
    apple.y = getRandomInt(0, canvas.height / grid) * grid;

    // Ẩn modal Game Over/Winning nếu đang hiển thị
    document.getElementById('gameOverModal').style.display = 'none';

    // Cập nhật điểm số hiển thị
    updateScoreDisplay();

    // Bắt đầu lại vòng lặp game
    requestAnimationFrame(loop);
}

//==========================
// Hàm cập nhật điểm số hiển thị
//==========================
function updateScoreDisplay() {
    const scoreEl = document.getElementById('score');
    if (multiplayerMode) {
        scoreEl.innerText = `P1: ${score1} | P2: ${score2}`;
    } else {
        scoreEl.innerText = `Score: ${score1}`;
    }
}

//==========================
// Hàm hiển thị popup Game Over/Winning
//==========================
function showGameOverPopup() {
    // Nếu chế độ multiplayer, hiển thị thông điệp thắng/thua, nếu không thì hiển thị "Game Over"
    const titleEl = document.getElementById('gameOverTitle');
    titleEl.innerText = multiplayerMode ? winnerMessage : "Game Over!";
    // Hiển thị modal
    document.getElementById('gameOverModal').style.display = 'block';
}

//==========================
// Hàm xử lý thay đổi hướng di chuyển của snake
//==========================
// Dành cho snake (player) nhất định
function changeDirection(snake, direction) {
    // Kiểm tra để không cho snake quay ngược lại
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
// Hàm cập nhật vị trí và trạng thái của snake
//==========================
function updateSnake(snake, count, isBoosting, otherSnakeCells, updateScoreCallback) {
    // Tính effectiveInterval dựa trên boost
    let effectiveInterval = isBoosting ? Math.max(1, Math.floor(frameInterval / 2)) : frameInterval;
    if (count < effectiveInterval) {
        return false; // Chưa đủ frame để cập nhật
    }

    // Cập nhật vị trí của snake
    snake.x += snake.dx;
    snake.y += snake.dy;

    // Kiểm tra va chạm với tường (nếu wallsEnabled bật) hoặc thực hiện wrap
    if (wallsEnabled) {
        if (snake.x < 0 || snake.x >= canvas.width || snake.y < 0 || snake.y >= canvas.height) {
            return true; // Báo game over
        }
    } else {
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

    // Thêm vị trí đầu mới vào mảng cells của snake
    snake.cells.unshift({ x: snake.x, y: snake.y });
    if (snake.cells.length > snake.maxCells) {
        snake.cells.pop();
    }

    // Kiểm tra va chạm với thân snake (tự cắn mình)
    for (let i = 1; i < snake.cells.length; i++) {
        if (snake.x === snake.cells[i].x && snake.y === snake.cells[i].y) {
            return true; // Báo game over
        }
    }

    // Nếu có snake đối thủ, kiểm tra va chạm với thân đối thủ
    if (otherSnakeCells) {
        for (let cell of otherSnakeCells) {
            if (snake.x === cell.x && snake.y === cell.y) {
                return true; // Báo game over
            }
        }
    }

    // Kiểm tra nếu snake ăn được táo
    if (snake.x === apple.x && snake.y === apple.y) {
        snake.maxCells++;          // Tăng độ dài snake
        updateScoreCallback();       // Cập nhật điểm số cho snake
        // Đặt lại vị trí của táo một cách ngẫu nhiên
        apple.x = getRandomInt(0, canvas.width / grid) * grid;
        apple.y = getRandomInt(0, canvas.height / grid) * grid;
    }

    return false; // Không có va chạm gây game over
}

//==========================
// Hàm vẽ snake
//==========================
function drawSnake(snake, color) {
    ctx.fillStyle = color;
    for (let cell of snake.cells) {
        ctx.fillRect(cell.x, cell.y, grid - 1, grid - 1);
    }
}

//==========================
// Vòng lặp game chính
//==========================
function loop() {
    if (gameOver) return;

    // Xóa canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Vẽ táo
    ctx.fillStyle = 'red';
    ctx.fillRect(apple.x, apple.y, grid - 1, grid - 1);

    // Tăng biến đếm cho snake1 và snake2
    count1++;
    if (multiplayerMode) {
        count2++;
    }

    // Cập nhật snake1
    let gameOverSnake1 = updateSnake(
        snake1,
        count1,
        isBoosting1,
        multiplayerMode && snake2 ? snake2.cells : null,
        () => { score1++; updateScoreDisplay(); }
    );
    // Nếu snake1 gây game over, trong chế độ multiplayer thì player2 thắng
    if (gameOverSnake1) {
        gameOver = true;
        if (multiplayerMode) {
            winnerMessage = "Player 2 Wins!";
        }
    } else {
        if (count1 >= (isBoosting1 ? Math.max(1, Math.floor(frameInterval / 2)) : frameInterval)) {
            count1 = 0;
        }
    }

    // Nếu chế độ multiplayer bật, cập nhật snake2
    if (multiplayerMode && snake2) {
        let gameOverSnake2 = updateSnake(
            snake2,
            count2,
            isBoosting2,
            snake1.cells,
            () => { score2++; updateScoreDisplay(); }
        );
        if (gameOverSnake2) {
            gameOver = true;
            if (multiplayerMode) {
                // Nếu cả snake1 và snake2 gặp lỗi cùng lúc, thì hoà
                if (gameOverSnake1) {
                    winnerMessage = "Draw!";
                } else {
                    winnerMessage = "Player 1 Wins!";
                }
            }
        } else {
            if (count2 >= (isBoosting2 ? Math.max(1, Math.floor(frameInterval / 2)) : frameInterval)) {
                count2 = 0;
            }
        }
    }

    // Vẽ snake1 (màu xanh lá)
    drawSnake(snake1, 'lime');
    // Nếu multiplayer, vẽ snake2 (màu vàng)
    if (multiplayerMode && snake2) {
        drawSnake(snake2, 'yellow');
    }

    // Nếu game kết thúc, hiển thị popup Game Over/Winning
    if (gameOver) {
        showGameOverPopup();
    } else {
        requestAnimationFrame(loop);
    }
}

// Bắt đầu vòng lặp game
requestAnimationFrame(loop);

//==========================
// Xử lý sự kiện bàn phím cho điều khiển và boost
//==========================
document.addEventListener('keydown', function (e) {
    if (multiplayerMode) {
        // --- Chế độ Multiplayer ---
        // Player1: sử dụng các phím mũi tên, boost với 'Enter'
        if (e.key === 'ArrowLeft') {
            changeDirection(snake1, 'left');
        } else if (e.key === 'ArrowUp') {
            changeDirection(snake1, 'up');
        } else if (e.key === 'ArrowRight') {
            changeDirection(snake1, 'right');
        } else if (e.key === 'ArrowDown') {
            changeDirection(snake1, 'down');
        } else if (e.key === 'Enter') {
            isBoosting1 = true;
        }
        // Player2: sử dụng WSAD, boost với Space
        else if (e.key === 'a' || e.key === 'A') {
            changeDirection(snake2, 'left');
        } else if (e.key === 'w' || e.key === 'W') {
            changeDirection(snake2, 'up');
        } else if (e.key === 'd' || e.key === 'D') {
            changeDirection(snake2, 'right');
        } else if (e.key === 's' || e.key === 'S') {
            changeDirection(snake2, 'down');
        } else if (e.key === ' ') {
            isBoosting2 = true;
        }
    } else {
        // --- Chế độ Singleplayer ---
        // Sử dụng cả mũi tên và WSAD để điều khiển snake1, boost với Space
        if (e.key === 'ArrowLeft' || e.key === 'a' || e.key === 'A') {
            changeDirection(snake1, 'left');
        } else if (e.key === 'ArrowUp' || e.key === 'w' || e.key === 'W') {
            changeDirection(snake1, 'up');
        } else if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') {
            changeDirection(snake1, 'right');
        } else if (e.key === 'ArrowDown' || e.key === 's' || e.key === 'S') {
            changeDirection(snake1, 'down');
        } else if (e.key === ' ' || e.key === 'Spacebar' || e.code === 'Space') {
            isBoosting1 = true;
        }
    }
});

// Lắng nghe sự kiện nhả phím để tắt chế độ boost
document.addEventListener('keyup', function (e) {
    if (multiplayerMode) {
        if (e.key === 'Enter') {
            isBoosting1 = false;
        } else if (e.key === ' ' || e.key === 'Spacebar' || e.code === 'Space') {
            isBoosting2 = false;
        }
    } else {
        if (e.key === ' ' || e.key === 'Spacebar' || e.code === 'Space') {
            isBoosting1 = false;
        }
    }
});

//==========================
// Xử lý sự kiện cho các nút điều khiển WSAD
//==========================
document.getElementById('btn-w').addEventListener('click', function () {
    if (multiplayerMode && snake2) {
        changeDirection(snake2, 'up');
    } else {
        changeDirection(snake1, 'up');
    }
});
document.getElementById('btn-a').addEventListener('click', function () {
    if (multiplayerMode && snake2) {
        changeDirection(snake2, 'left');
    } else {
        changeDirection(snake1, 'left');
    }
});
document.getElementById('btn-s').addEventListener('click', function () {
    if (multiplayerMode && snake2) {
        changeDirection(snake2, 'down');
    } else {
        changeDirection(snake1, 'down');
    }
});
document.getElementById('btn-d').addEventListener('click', function () {
    if (multiplayerMode && snake2) {
        changeDirection(snake2, 'right');
    } else {
        changeDirection(snake1, 'right');
    }
});

//==========================
// Xử lý nút Reset trong popup Game Over/Winning
//==========================
document.getElementById('resetBtn').addEventListener('click', function () {
    resetGame();
});

//==========================
// Xử lý nút Settings và modal popup
//==========================
const settingsBtn = document.getElementById('settingsBtn');
const settingsModal = document.getElementById('settingsModal');
const modalClose = document.getElementById('modalClose');
const settingsForm = document.getElementById('settingsForm');

// Khi nhấn nút Settings, hiển thị modal popup và đưa giá trị hiện tại vào form
settingsBtn.addEventListener('click', function () {
    document.getElementById('speed').value = speedSetting;
    document.getElementById('gridSize').value = grid;
    document.getElementById('wallsEnabled').checked = wallsEnabled;
    document.getElementById('multiplayerEnabled').checked = multiplayerMode;
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
    multiplayerMode = document.getElementById('multiplayerEnabled').checked;

    // Cập nhật frameInterval dựa trên tốc độ mới
    frameInterval = 11 - speedSetting;

    // Lưu cài đặt vào localStorage
    localStorage.setItem('speedSetting', speedSetting);
    localStorage.setItem('gridSize', grid);
    localStorage.setItem('wallsEnabled', wallsEnabled);
    localStorage.setItem('multiplayerMode', multiplayerMode);

    // Nếu chuyển sang chế độ multiplayer và snake2 chưa được khởi tạo, tạo snake2
    if (multiplayerMode && !snake2) {
        snake2 = {
            x: grid * 15,
            y: grid * 15,
            dx: 0,
            dy: grid,
            cells: [],
            maxCells: 4
        };
    } else if (!multiplayerMode) {
        snake2 = null;
    }

    // Đóng modal Settings
    settingsModal.style.display = 'none';
    // Reset game để áp dụng cài đặt mới
    resetGame();
});
