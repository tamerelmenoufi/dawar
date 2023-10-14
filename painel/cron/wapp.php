<?php


    function EnviarWappNovo($n, $m){
        $postdata = http_build_query(
            array(
                'numero' => $n, // Receivers phonei
                'mensagem' => $m,
                'cnf' => ['ddi' => '+'],
            )
            );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents('http://wapp.mohatron.com/', false, $context);
    }


    $path = "./wapp/";
    $dir = dir($path);

    while($file = $dir -> read()){
        if(is_file($path.$file)){
            $json = file_get_contents($path.$file);
            $send = json_decode($json);
            $data = print_r($send, true);
            // file_put_contents('result.txt', $path.$file." || ".$data. " || ".date("d/m/Y H:i:s")."\n\n\n\n", FILE_APPEND | LOCK_EX);
            unlink($path.$file);
            EnviarWappNovo($send->numero, $send->mensagem);
            // echo $file."\n";

        }
    }
    $dir -> close();