<?php
header("Content-Type: application/json; charset=UTF-8");

require '../libs/Database.php';
require '../config.php';
require '../helpers/functions.php';

// verificando se a sessão está ativa
if (!sessao_ativa()) session_start();

// verificando se tem algum user logado na sessao
if (isset($_SESSION['user']['id'])) {

    $user_logado = $_SESSION['user']['nome'];

    // instanciando banco de dados
    $db = new database(
        MYSQL_CONFIG['host'],
        MYSQL_CONFIG['database'],
        MYSQL_CONFIG['username'],
        MYSQL_CONFIG['password']
    );

    $sql_enquetes = "SELECT * FROM enquetes";
    $enquetes = $db->query($sql_enquetes);


    if (!empty($enquetes)) {

        echo json_encode([
            'status' => 'success',
            'user' => $user_logado,
            'enquetes' => $enquetes
        ]);
    }
} else {
    echo json_encode([
        'status' => 'fail',
        'user' => 'none',
        'enquetes' => 'none'
    ]);
    exit;
}


// echo json_encode([
//     'status' => 'success',
//     'user' => $_SESSION['user']['nome']
// ]);