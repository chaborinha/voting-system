<?php
header("Content-Type: application/json; charset=UTF-8");

# importação de arquivos
include '../libs/database.php';
include '../config.php';
include '../helpers/functions.php';

# verificando se requisição foi feita com o método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # pegando dados do front end
    $data = json_decode(file_get_contents('php://input'), true);

    # pega o token do reCAPTCHA enviado pelo front-end
    $recaptcha_response = $data['g-recaptcha-response'];

    # verificando se o reCAPTCHA está vazio
    if (empty($recaptcha_response)) {
        echo json_encode([
            'status' => 'error',
            'mensagem' => 'Token do reCAPTCHA ausente.'
        ]);
    }

    # faz a requisição cURL para validar o reCAPTCHA
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret' => ACCESS_TOKEN,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $resultado = json_decode($response, true);

    if ($resultado['success'] === true) {

        $input_email = $data['email'] ?? '';
        $input_senha = $data['senha'] ?? '';

        # verificando se o email e a senha foram fornecidos
        if (empty($input_email) || empty($input_senha)) {
            echo json_encode([
                'status' => 'empty_credentials',
                'mensagem' => 'Email e senha são obrigatórios.'
            ]);
            exit;
        }

        $db = new database(
            MYSQL_CONFIG['host'],
            MYSQL_CONFIG['database'],
            MYSQL_CONFIG['username'],
            MYSQL_CONFIG['password']
        );

        # consultar o banco de dados para verificar se o usuário existe
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $params = [':email' => $input_email];
        $verifica_usuario = $db->query($sql, $params);

        # verificar se o usuário foi encontrado
        if (!empty($verifica_usuario)) {
            if ($input_senha == $verifica_usuario[0]['senha'])
            {
                echo json_encode([
                    'status' => 'logged',
                    'mensagem' => 'usuario logado',
                    'user_name' => $verifica_usuario[0]['nome']
                ]);

                if (!sessao_ativa())
                {
                    session_start();
                    $_SESSION['user'] = $verifica_usuario[0];
                    unset($_SESSION['user']['senha']);
                }

            } else {
                echo json_encode([
                    'status' => 'logged_fail',
                    'mensagem' => 'credenciais errada',
                ]);
            }
        }
        
}

}