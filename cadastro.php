<?php
// Mostra erros do PHP (apenas durante o desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conexao.php");

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protege contra SQL Injection
    $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Verifica se o e-mail já está cadastrado
    $checkEmail = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Este e-mail já está cadastrado! Tente outro.');
            window.location.href = 'cadastro.html';
        </script>";
        exit;
    }

    // Faz o cadastro
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Cadastro realizado com sucesso!');
            window.location.href = 'login.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Erro ao cadastrar: " . addslashes($conn->error) . "');
            window.location.href = 'cadastro.html';
        </script>";
        exit;
    }
}
?>

