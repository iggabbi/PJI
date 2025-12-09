const turtle = document.getElementById("turtle");
const container = document.getElementById("gameContainer");
const scoreDisplay = document.getElementById("score");

let isJumping = false;
let velocity = 0;
const gravity = 0.8;
const jumpPower = 15;
let position = 0;
let score = 0;
let gameOver = false;

const obstacles = [];

// Pular com espaço ou seta pra cima
document.addEventListener("keydown", (e) => {
  if ((e.code === "Space" || e.code === "ArrowUp") && !isJumping && !gameOver) {
    isJumping = true;
    velocity = jumpPower;
  }
});

function createObstacle() {
  if (gameOver) return;

  const obstacle = document.createElement("img");
  const types = ["images/jogo/sacola1.png", "images/jogo/sacola2.png", "images/jogo/garrafa.png"];
  const imgSrc = types[Math.floor(Math.random() * types.length)];

  obstacle.src = imgSrc;
  obstacle.classList.add("obstacle");
  obstacle.style.width = "35px";
  obstacle.style.height = "auto";
  obstacle.style.position = "absolute";
  obstacle.style.bottom = "30px"; // em cima da areia
  obstacle.style.left = container.offsetWidth + "px";

  container.appendChild(obstacle);

  obstacles.push({
    element: obstacle,
    x: container.offsetWidth,
    width: 35,
    height: 35,
  });
}

function updateObstacles() {
  for (let i = obstacles.length - 1; i >= 0; i--) {
    const obs = obstacles[i];
    obs.x -= 8; // velocidade dos obstáculos
    obs.element.style.left = obs.x + "px";

    // Se saiu da tela → remove e conta ponto
    if (obs.x + obs.width < 0) {
      obs.element.remove();
      obstacles.splice(i, 1);
      score++;
      scoreDisplay.textContent = "Tartarugas Salvas: " + score;
    }
  }
}

function checkCollision() {
  const turtleRect = turtle.getBoundingClientRect();

  for (const obs of obstacles) {
    const obsRect = obs.element.getBoundingClientRect();
    if (
      turtleRect.right > obsRect.left &&
      turtleRect.left < obsRect.right &&
      turtleRect.bottom > obsRect.top &&
      turtleRect.top < obsRect.bottom
    ) {
      return true;
    }
  }
  return false;
}

function gameLoop() {
  if (gameOver) return;

  // Física do pulo
  if (isJumping) {
    position += velocity;
    velocity -= gravity;

    if (position <= 0) {
      position = 0;
      isJumping = false;
    }
  }

  // Atualiza posição da tartaruga
  turtle.style.bottom = 30 + position + "px";

  updateObstacles();

  if (checkCollision()) {
    gameOver = true;
    alert(`Fim de Jogo! Você salvou ${score} tartarugas.`);
    location.reload();
    return;
  }

  requestAnimationFrame(gameLoop);
}

// Gera obstáculos periodicamente
setInterval(() => {
  if (!gameOver) createObstacle();
}, 1500);

gameLoop();
