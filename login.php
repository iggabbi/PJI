<?php
include("conexao.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Proteção contra SQL Injection
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $senha = $_POST["senha"];

  // Busca o usuário pelo e-mail
  $sql = "SELECT * FROM usuarios WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verifica se a senha está correta
    if (password_verify($senha, $user["senha"])) {
      $_SESSION["usuario_id"] = $user["id"];
      $_SESSION["usuario_nome"] = $user["nome"];

      // Redireciona para o fórum
      header("Location: forum.php");
      exit;
    } else {
      echo "<script>
        alert('Senha incorreta!');
        window.location.href = 'login.html';
      </script>";
      exit;
    }
  } else {
    echo "<script>
      alert('Usuário não encontrado!');
      window.location.href = 'login.html';
    </script>";
    exit;
  }
}
?>

