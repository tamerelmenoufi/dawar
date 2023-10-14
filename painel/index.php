<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");

    if($_GET['s']){
        $lng = $_SESSION['lng'];
        $_SESSION = [];
        $_SESSION['lng'] = $lng;
        header("location:./");
        exit();
    }

    if($_GET['lng']){
        $_SESSION['lng'] = $_GET['lng'];
    }

    if($_SESSION['ProjectPainel']){
        $url = "src/home/index.php";
    }else{
        $url = "src/login/index.php";
    }
?>
<!doctype html>
<!-- <html lang="en"> -->
<html lang="<?=$_SESSION['lng']?>" <?=(($_SESSION['lng'] == 'ar')?'dir="rtl"':false)?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <title>TCV - Panel</title>
    <?php
    include("lib/header.php");
    ?>
  </head>
  <style>
body {

    background: <?=(($_SESSION['ProjectPainel'])?'#fff':'url(img/fundopncred.jpg)')?>;
    /* no-repeat center fixed: ; */
    background-size: auto auto;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
}

</style>

  <body>

    <div class="Carregando">
        <div><i class="fa-solid fa-rotate fa-pulse"></i></div>
    </div>

    <div class="bodyApp"></div>

    <?php
    include("lib/footer.php");
    ?>

    <script>
        $(function(){
            Carregando();
            $.ajax({
                url:"<?=$url?>",
                success:function(dados){
                    $(".bodyApp").html(dados);
                }
            });
        })


        //Jconfirm
        jconfirm.defaults = {
            typeAnimated: true,
            type: "blue",
            smoothContent: true,
        }

    </script>

  </body>
</html>