<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $assunto = trim($_POST["assunto"]);
    $mensagem = trim($_POST["mensagem"]);

    // Configurações do servidor de e-mail (Gmail)
    $mail = new PHPMailer(true);

    try {
        // Configura o servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        
        // ⚠️ Coloque aqui seu e-mail e senha de aplicativo do Gmail
        $mail->Username = 'gabriela.tizeu@aluno.ifsp.edu.br';
        $mail->Password = 'theh vtsw qvwp qqxr'; 
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remetente e destinatário
        $mail->setFrom($email, $nome);
        $mail->addAddress('gabriela.tizeu@aluno.ifsp.edu.br', 'As Splash');

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = "📩 Novo contato: $assunto";
        $mail->Body = "
            <h2>Nova mensagem do site As Splash:</h2>
            <p><strong>Nome:</strong> $nome</p>
            <p><strong>E-mail:</strong> $email</p>
            <p><strong>Assunto:</strong> $assunto</p>
            <p><strong>Mensagem:</strong><br>" . nl2br($mensagem) . "</p>
        ";

        $mail->send();
        echo "<script>alert('Mensagem enviada com sucesso! 🌊'); window.location.href='contato.html';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Erro ao enviar: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>

