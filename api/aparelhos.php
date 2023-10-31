<?php
  
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");
    $con = AppConnect('dawar');
    $conApi = AppConnect('information_schema');
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
    

    $query = "REPLACE INTO aparelhos set unicoId = '{$_POST['id']}', nome = '{$_POST['nome']}', telefone = '{$_POST['telefone']}'";
    $result = mysqli_query($con, $query);

    echo json_encode(['status' => $query]);