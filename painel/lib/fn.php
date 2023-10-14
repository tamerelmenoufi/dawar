<?php

    function dataBr($dt){
        list($d, $h) = explode(" ",$dt);
        list($y,$m,$d) = explode("-",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$d}/{$m}/$y".(($h)?" {$h}":false);
        }
        return $data;
    }

    function dataMysql($dt){
        list($d, $h) = explode(" ",$dt);
        list($d,$m,$y) = explode("/",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$y}-{$m}-$d".(($h)?" {$h}":false);
        }
        return $data;
    }

    function EnviarWappNovo($n, $m, $app = false){
        $postdata = array(
                'numero' => $n, // Receivers phonei
                'mensagem' => $m,
              );
        if($app){
            file_put_contents("../../../painel/cron/wapp/".date("YmdHis").".txt", json_encode($postdata));
        }else{
            file_put_contents("../../cron/wapp/".date("YmdHis").".txt", json_encode($postdata));
        }

    }
