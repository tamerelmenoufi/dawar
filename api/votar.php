<?php
  
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");
    $con = AppConnect('dawar');
    $conApi = AppConnect('information_schema');
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
    

    $query = "INSERT INTO  votos set produto = '{$_POST['cod']}', tipo = '{$_POST['tipo']}', id = '{$_POST['uid']}', data = NOW()";
    $result = mysqli_query($con, $query);


    echo json_encode(['status' => 'Success']);