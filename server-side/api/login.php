<?php

header("Content-Type: application/json; charset=UTF-8");

require '../helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    // pega o token do reCAPTCHA enviado pelo front-end
    $recaptcha_response = $data['g-recaptcha-response'];

    if (empty($recaptcha_response)) {
        echo json_encode([
            'status' => 'error',
            'mensagem' => 'Token do reCAPTCHA ausente.'
        ]);
        exit;
    }

    // faz a requisição cURL para validar o reCAPTCHA
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret' => '6Lc5FzYrAAAAAHBZQXI5duwJiiP5qtg3hrgGS8mr',
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $resultado = json_decode($response, true);

    if ($resultado['success'] === true) {

        $nome = $data['nome'] ?? '';
        $senha = $data['senha'] ?? '';

        if (empty($nome) || empty($senha)) {
            echo json_encode([
                'status' => 'empty_credentials',
                'mensagem' => 'Usuário e senha são obrigatórios.'
            ]);
            exit;
        }

        $usuario_valido = 'admin';
        $senha_valida = '123456';

        if ($nome === $usuario_valido && $senha === $senha_valida) {
            
            echo json_encode([
                'status' => 'success',
                'mensagem' => 'Login realizado com sucesso.'
            ]);

            // criando uma sessão para o usuario
            if(!sessao_ativa())
            {
                session_start();
                $_SESSION['user'] = $usuario_valido;
            }

        } else {
            
            echo json_encode([
                'status' => 'invalid_credentials',
                'mensagem' => 'Credenciais inválidas.'
            ]);
        }

    } else {
       
        echo json_encode([
            'status' => 'recaptcha_failed',
            'mensagem' => 'Falha na verificação do reCAPTCHA.',
            'erro' => $resultado['error-codes'] ?? 'desconhecido'
        ]);
    }

} else {
   
    echo json_encode([
        'status' => 'method_error',
        'mensagem' => 'Método HTTP inválido.'
    ]);
}
?>
