<?php
session_start();
include("conexao.php");

// Verifica login
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.html");
  exit;
}

// Verifica se há ID do tópico na URL
if (!isset($_GET["id"])) {
  echo "Tópico não encontrado.";
  exit;
}

$id_topico = intval($_GET["id"]);

// Busca informações do tópico
$sql_topico = "SELECT * FROM topicos WHERE id = $id_topico";
$result_topico = $conn->query($sql_topico);

if ($result_topico->num_rows == 0) {
  echo "Tópico não encontrado.";
  exit;
}

$topico = $result_topico->fetch_assoc();

// Envia novo comentário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comentario"])) {
  $comentario = mysqli_real_escape_string($conn, $_POST["comentario"]);
  $autor = $_SESSION["usuario_nome"];
  $sql_com = "INSERT INTO comentarios (id_topico, autor, comentario) VALUES ($id_topico, '$autor', '$comentario')";
  $conn->query($sql_com);
  header("Location: topico.php?id=$id_topico"); // Recarrega a página
  exit;
}

// Busca comentários
$sql_comentarios = "SELECT * FROM comentarios WHERE id_topico = $id_topico ORDER BY data_comentario ASC";
$comentarios = $conn->query($sql_comentarios);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style-topico.css">
    <script src="scripts/menu.js" defer></script>
    <title>As Splash</title>
</head>
<body>

  <header id="header">
        <div class="interface">
            <div class="logo">
                <a href="index.html">
                    <img src="images/index/logo.png" alt="logotipo">
                </a>
            </div>

            <nav class="menu-desktop">
                <ul>
                    <li><a href="index.html">Início</a></li>
                    <li><a href="time.html">Sobre</a></li>
                    <li><a href="tartarugas.html">Tartarugas</a></li>
                    <li><a href="projetos.html">Projetos</a></li>
                </ul>
            </nav>

            <div class="btn-contato">
                <a href="login.html">
                    <button>Acesse a Comunidade</button>
                </a>
            </div>

            <div class="btn-abrir-menu" id="btn-menu">
                <i class="bi bi-list"></i>
            </div>
        </div>
  </header>

  <section class="topo">
    <h1>Comunidade</h1>
    <a href="forum.php" class="voltar">← Voltar ao Fórum</a>
  </section>

  <main class="container">
    <article class="topico">
      <h2><?php echo htmlspecialchars($topico["titulo"]); ?></h2>
      <p class="conteudo"><?php echo nl2br(htmlspecialchars($topico["conteudo"])); ?></p>
      <span class="autor">Por <?php echo htmlspecialchars($topico["autor"]); ?> em <?php echo $topico["data_criacao"]; ?></span>
    </article>

    <section class="comentarios">
      <h3>Comentários</h3>
      <?php if ($comentarios->num_rows > 0): ?>
        <?php while($c = $comentarios->fetch_assoc()): ?>
          <div class="comentario">
            <p><?php echo nl2br(htmlspecialchars($c["comentario"])); ?></p>
            <span>— <?php echo htmlspecialchars($c["autor"]); ?>, <?php echo $c["data_comentario"]; ?></span>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="sem-comentarios">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
      <?php endif; ?>
    </section>

    <section class="novo-comentario">
      <h3>Deixe seu comentário</h3>
      <form method="post" action="">
        <textarea name="comentario" placeholder="Escreva algo..." required></textarea>
        <button type="submit">Enviar</button>
      </form>
    </section>
  </main>
</body>
</html>
