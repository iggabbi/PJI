<?php
session_start();
include("conexao.php");

// Redireciona se não estiver logado
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.html");
  exit;
}

// Inserir novo tópico
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acao"]) && $_POST["acao"] === "criar") {
  $titulo = trim($_POST["titulo"]);
  $conteudo = trim($_POST["conteudo"]);
  $autor = $_SESSION["usuario_nome"];

  $stmt = $conn->prepare("INSERT INTO topicos (titulo, conteudo, autor) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $titulo, $conteudo, $autor);
  $stmt->execute();
  $stmt->close();

  header("Location: forum.php?msg=sucesso");
  exit;
}

// Deletar tópico (se o autor for o mesmo)
if (isset($_GET["delete"])) {
  $id_delete = intval($_GET["delete"]);
  $autor = $_SESSION["usuario_nome"];

  $stmt = $conn->prepare("DELETE FROM topicos WHERE id=? AND autor=?");
  $stmt->bind_param("is", $id_delete, $autor);
  $stmt->execute();
  $stmt->close();

  header("Location: forum.php?msg=deletado");
  exit;
}

// Buscar tópicos
$sql = "SELECT * FROM topicos ORDER BY data_criacao DESC";
$topicos = $conn->query($sql);
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
    <link rel="stylesheet" href="style-forum.css">
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
    <p>Bem-vindo(a) <strong><?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?></strong>!</p>
    <a href="logout.php" class="sair">Sair</a>
  </section>

  <main class="container">
    <?php if (isset($_GET["msg"]) && $_GET["msg"] === "sucesso"): ?>
      <p class="mensagem sucesso">✅ Tópico criado com sucesso!</p>
    <?php elseif (isset($_GET["msg"]) && $_GET["msg"] === "deletado"): ?>
      <p class="mensagem sucesso">🗑️ Tópico excluído.</p>
    <?php endif; ?>

    <section class="novo-topico">
      <h2>Criar novo tópico</h2>
      <form method="post" action="">
        <input type="hidden" name="acao" value="criar">
        <input type="text" name="titulo" placeholder="Título do tópico" required>
        <textarea name="conteudo" placeholder="Escreva aqui..." required></textarea>
        <button type="submit">Publicar</button>
      </form>
    </section>

    <section class="lista-topicos">
      <h2>Tópicos recentes</h2>

      <?php if ($topicos->num_rows > 0): ?>
        <?php while($t = $topicos->fetch_assoc()): ?>
          <div class="topico">
            <h3>
              <a href="topico.php?id=<?php echo $t['id']; ?>">
                <?php echo htmlspecialchars($t['titulo']); ?>
              </a>
            </h3>
            <p><?php echo nl2br(htmlspecialchars(substr($t['conteudo'], 0, 150))); ?>...</p>
            <span>Por <?php echo htmlspecialchars($t['autor']); ?> em <?php echo date("d/m/Y H:i", strtotime($t['data_criacao'])); ?></span>

            <?php if ($_SESSION["usuario_nome"] === $t["autor"]): ?>
              <a href="forum.php?delete=<?php echo $t['id']; ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este tópico?')">Excluir</a>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="sem-topicos">Nenhum tópico criado ainda. Que tal começar a conversa? 🐢</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>

