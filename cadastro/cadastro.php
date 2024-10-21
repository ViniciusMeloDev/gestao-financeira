<?php 

// Incluir o arquivo de conexão com o banco de dados
require_once '../bd/conexao.php';

// Iniciar a sessão
session_start();

if (isset($_POST['confirma-cadastro'])) {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirma-senha'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirmaSenha) {
        echo "As senhas não coincidem!";
        exit;
    }

    // Verifica se o e-mail é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido!";
        exit;
    }

    // Verifica se o e-mail já está cadastrado no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "Este e-mail já está cadastrado!";
        exit;
    }

    // Criptografa a senha antes de armazenar
    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere os dados no banco de dados
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    if ($stmt->execute([$nome, $email, $senhaCriptografada])) {
        // Capturar o ID do usuário recém-cadastrado
        $usuario_id = $pdo->lastInsertId();

        // Armazenar o ID do usuário na sessão
        $_SESSION['usuario_id'] = $usuario_id;

        echo "Cadastro realizado com sucesso!";
        // Redireciona o usuário para o formulário de investidor
        header("Location: form.php");
        exit;
    } else {
        echo "Erro ao cadastrar usuário!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Financeira</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <section class="fundo-geral">
        <div class="container">
            <div class="formulario-cadastro">
                <h1 class="text-center text-white"><i class="fa fa-lock"></i> Cadastro</h1>
              
                <form action="" method="POST">
                <div class="form-group">
                        <label><i class="fa fa-envelope"></i>Nome:</label>
                        <input type="text" class="form-control" name="nome" placeholder="Insira seu nome">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-envelope"></i> E-mail:</label>
                        <input type="text" class="form-control" name="email" placeholder="Insira seu e-mail de acesso">
                    </div>
                    <div class="form-group">
                        <label><i class="fa-solid fa-key"></i> Senha:</label>
                        <input type="password" class="form-control" name="senha" placeholder="Insira sua senha">
                    </div>
                    <div class="form-group">
                        <label><i class="fa-solid fa-key"></i> Confirmar a senha:</label>
                        <input type="password" class="form-control" name="confirma-senha" placeholder="confirme sua senha">
                    </div>
                    <input type="submit" value="Confirmar cadastro" name="confirma-cadastro" class="btn btn-outline-light">
                    
                </form>
            </div>
            <p class="text-white text-center">Já possui um cadastro? <a href="../index.php" class="text-white">Faça Login clicando aqui</a></p>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>