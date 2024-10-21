<?php 
// Incluir o arquivo de conexão com o banco de dados
require_once '../bd/conexao.php';

// Verificar se o usuário está logado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redirecionar para a tela de login
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializar contadores
    $cont_conservador = 0;
    $cont_moderado = 0;
    $cont_arrojado = 0;

    // Capturar as respostas do formulário
    $respostas = [
        'objetivo' => $_POST['objetivo'],
        'reacao' => $_POST['reacao'],
        'frequencia' => $_POST['frequencia'],
        'prazo' => $_POST['prazo'],
        'risco' => $_POST['risco'],
        'distribuicao' => $_POST['distribuicao']
    ];

    // Contar quantas vezes cada perfil foi escolhido
    foreach ($respostas as $resposta) {
        if ($resposta == "conservador") {
            $cont_conservador++;
        } elseif ($resposta == "moderado") {
            $cont_moderado++;
        } elseif ($resposta == "arrojado") {
            $cont_arrojado++;
        }
    }

    // Definir o perfil predominante
    $perfil_investidor = "conservador"; // Padrão em caso de empate
    if ($cont_moderado > $cont_conservador && $cont_moderado > $cont_arrojado) {
        $perfil_investidor = "moderado";
    } elseif ($cont_arrojado > $cont_conservador && $cont_arrojado > $cont_moderado) {
        $perfil_investidor = "arrojado";
    }

    // Atualizar o campo tipo_investidor para o usuário logado
    $sql = "UPDATE usuarios SET tipo_investidor = :tipo_investidor WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);

    try {
        // Vincular os parâmetros
        $stmt->bindParam(':tipo_investidor', $perfil_investidor);
        $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
        $stmt->execute();

        echo "Perfil de investidor atualizado com sucesso!";

        // Redirecionar o usuário para o painel correspondente
        if ($perfil_investidor == "conservador") {
            header("Location: ../painel/painel_conservador.php");
        } elseif ($perfil_investidor == "moderado") {
            header("Location: ../painel/painel_moderado.php");
        } elseif ($perfil_investidor == "arrojado") {
            header("Location: ../painel/painel_arrojado.php");
        }
        exit; // Certificar-se de que o script termina após o redirecionamento
    } catch (PDOException $e) {
        echo "Erro ao atualizar perfil de investidor: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Investidor</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../../formulario.css">
    </head>
    <body>

    <div class="fundo-geral">
        <div class="formulario-investidor">
            <h1><i class="fa-solid fa-user"></i> Perfil de Investidor</h1>
            <form method="POST">
                <!-- Pergunta 1 -->
                <div class="form-group">
                    <label>Qual é o seu objetivo principal ao investir?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="objetivo" id="objetivo1" value="conservador">
                        <label class="form-check-label" for="objetivo1">
                            a) Preservar meu capital 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="objetivo" id="objetivo2" value="moderado">
                        <label class="form-check-label" for="objetivo2">
                            b) Crescer meu patrimônio de forma gradual 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="objetivo" id="objetivo3" value="arrojado">
                        <label class="form-check-label" for="objetivo3">
                            c) Obter o maior retorno possível, mesmo com risco 
                        </label>
                    </div>
                </div>

                <!-- Pergunta 2 -->
                <div class="form-group">
                    <label>Como você reage a uma queda de 10% no valor do seu investimento?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reacao" id="reacao1" value="conservador">
                        <label class="form-check-label" for="reacao1">
                            a) Fico preocupado e penso em vender 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reacao" id="reacao2" value="moderado">
                        <label class="form-check-label" for="reacao2">
                            b) Analiso a situação e talvez faça ajustes 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reacao" id="reacao3" value="arrojado">
                        <label class="form-check-label" for="reacao3">
                            c) Vejo como uma oportunidade para investir mais 
                        </label>
                    </div>
                </div>

                <!-- Pergunta 3 -->
                <div class="form-group">
                    <label>Com que frequência você acompanha o mercado financeiro?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frequencia" id="frequencia1" value="conservador">
                        <label class="form-check-label" for="frequencia1">
                            a) Raramente, prefiro investimentos mais estáveis 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frequencia" id="frequencia2" value="moderado">
                        <label class="form-check-label" for="frequencia2">
                            b) Regularmente, gosto de entender como está o mercado 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frequencia" id="frequencia3" value="arrojado">
                        <label class="form-check-label" for="frequencia3">
                            c) Constantemente, estou sempre atento às mudanças 
                        </label>
                    </div>
                </div>

                <!-- Pergunta 4 -->
                <div class="form-group">
                    <label>Qual é o prazo médio para alcançar seus objetivos financeiros?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="prazo" id="prazo1" value="conservador">
                        <label class="form-check-label" for="prazo1">
                            a) Curto prazo (até 2 anos) 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="prazo" id="prazo2" value="moderado">
                        <label class="form-check-label" for="prazo2">
                            b) Médio prazo (3 a 5 anos) 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="prazo" id="prazo3" value="arrojado">
                        <label class="form-check-label" for="prazo3">
                            c) Longo prazo (mais de 5 anos) 
                        </label>
                    </div>
                </div>

                <!-- Pergunta 5 -->
                <div class="form-group">
                    <label>Como você descreveria sua tolerância a riscos financeiros?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="risco" id="risco1" value="conservador">
                        <label class="form-check-label" for="risco1">
                            a) Baixa, prefiro segurança 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="risco" id="risco2" value="moderado">
                        <label class="form-check-label" for="risco2">
                            b) Moderada, aceito riscos controlados 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="risco" id="risco3" value="arrojado">
                        <label class="form-check-label" for="risco3">
                            c) Alta, estou disposto a arriscar para maiores retornos 
                        </label>
                    </div>
                </div>

                <!-- Pergunta 6 -->
                <div class="form-group">
                    <label>Como você distribuiria R$ 100.000 em investimentos?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="distribuicao" id="distribuicao1" value="conservador">
                        <label class="form-check-label" for="distribuicao1">
                            a) A maior parte em renda fixa, para garantir segurança 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="distribuicao" id="distribuicao2" value="moderado">
                        <label class="form-check-label" for="distribuicao2">
                            b) Um equilíbrio entre renda fixa e variável 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="distribuicao" id="distribuicao3" value="arrojado">
                        <label class="form-check-label" for="distribuicao3">
                            c) A maior parte em renda variável, buscando maior retorno 
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-light btn-block">Enviar Respostas</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
