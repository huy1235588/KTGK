const gameContainer = document.querySelector('.game-container');
const player = document.getElementById('player');
const computer = document.getElementById('computer');
const ball = document.getElementById('ball');
const playerScore = document.getElementById('player-score');
const computerScore = document.getElementById('computer-score');

let playerY = 210;
let computerY = 210;
let ballX = 390;
let ballY = 240;
let ballSpeedX = 5;
let ballSpeedY = 5;
let playerPoints = 0;
let computerPoints = 0;

// Di chuyển paddle của người chơi bằng chuột
gameContainer.addEventListener('mousemove', (e) => {
    const rect = gameContainer.getBoundingClientRect();
    playerY = e.clientY - rect.top - 40;
    if (playerY < 0) playerY = 0;
    if (playerY > 420) playerY = 420;
    player.style.top = playerY + 'px';
});

function updateGame() {
    // Di chuyển bóng
    ballX += ballSpeedX;
    ballY += ballSpeedY;

    // AI cho computer paddle
    const computerCenter = computerY + 40;
    if (computerCenter < ballY - 35) computerY += 4;
    if (computerCenter > ballY + 35) computerY -= 4;
    if (computerY < 0) computerY = 0;
    if (computerY > 420) computerY = 420;

    // Va chạm với tường trên và dưới
    if (ballY <= 0 || ballY >= 480) {
        ballSpeedY = -ballSpeedY;
    }

    // Va chạm với paddle
    if (
        (ballX <= 30 && ballX >= 20 && ballY >= playerY && ballY <= playerY + 80) ||
        (ballX >= 750 && ballX <= 760 && ballY >= computerY && ballY <= computerY + 80)
    ) {
        ballSpeedX = -ballSpeedX * 1.05; // Tăng tốc độ sau mỗi lần chạm
        ballSpeedY += (Math.random() - 0.5) * 2; // Thêm độ ngẫu nhiên
    }

    // Ghi điểm
    if (ballX <= 0) {
        computerPoints++;
        resetBall();
    } else if (ballX >= 780) {
        playerPoints++;
        resetBall();
    }

    // Cập nhật vị trí
    player.style.top = playerY + 'px';
    computer.style.top = computerY + 'px';
    ball.style.left = ballX + 'px';
    ball.style.top = ballY + 'px';
    playerScore.textContent = playerPoints;
    computerScore.textContent = computerPoints;

    requestAnimationFrame(updateGame);
}

function resetBall() {
    ballX = 390;
    ballY = 240;
    ballSpeedX = 5 * (Math.random() > 0.5 ? 1 : -1);
    ballSpeedY = 5 * (Math.random() > 0.5 ? 1 : -1);
}

updateGame();
