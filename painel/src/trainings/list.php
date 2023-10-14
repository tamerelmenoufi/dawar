<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");


    if($_POST['action'] == 'add'){

        $query = "insert into treinee_admission set
                    company = '{$_POST['company']}',
                    training = '{$_POST['training']}',
                    trainee = '{$_POST['trainee']}',
                    treining_period = '{$_POST['treining_period']}',
                    initial_date = (select initial_date from treining_period where id='{$_POST['treining_period']}'),
                    final_date = (select final_date from treining_period where id='{$_POST['treining_period']}'),
                    cost = (select cost from treining_period where id='{$_POST['treining_period']}')
                ";
        mysqli_query($con, $query);
        exit();
    }

    if($_POST['action'] == 'delete'){

        $query = "update treinee_admission set del = '1', justify_del = '{$_POST['justify']}' where id = '{$_POST['id']}'";
        mysqli_query($con, $query);
        exit();
    }

?>
<ul class="list-group">
<?php

    if($_POST['search']){
        $search = "
            and (name like '%{$_POST['search']}%' or
            identity = '{$_POST['search']}' or
            phone =  '{$_POST['search']}')
        ";
    }

    $query = "select * from treinee_admission where treining_period = '{$_POST['treining_period']}' and del != '1'";
    $result = mysqli_query($con, $query);
    $ListAdd = [];
    while($d = mysqli_fetch_object($result)){
        $ListAdd[$d->id] = $d->trainee;
        $ListAddCost[$d->id] = $d->cost_pay;
    }

    $query = "select * from trainee where 1 {$search} order by name asc";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result)){
    while($d = mysqli_fetch_object($result)){
?>
    <li class="list-group-item d-flex justify-content-between align-items-center <?=(in_array($d->id, $ListAdd)?'bg-success bg-opacity-10':false)?>">
        <?=$d->name?>
        <div>
            <button editTrainee="<?=$d->id?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-user-pen"></i></button>
            <?php
            if(array_search($d->id, $ListAdd)){
                if($ListAddCost[array_search($d->id, $ListAdd)] == '0'){
            ?>
            <button payTrainee="<?=array_search($d->id, $ListAdd)?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-sack-dollar"></i></button>
            <button actionRemoveTrainee="<?=array_search($d->id, $ListAdd)?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-user-minus"></i></button>
            <?php
                }else{
            ?>
            <button removePayTrainee="<?=array_search($d->id, $ListAdd)?>" class="btn btn-success btn-sm"><i class="fa-solid fa-sack-dollar"></i></button>
            <?php
                }
            ?>


<div class="btn-group">
  <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa-regular fa-face-flushed"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li><button class="dropdown-item active" type="button"><i class="fa-regular fa-face-flushed"></i> <?=$Dic['registered']?></button></li>
    <li><button class="dropdown-item" type="button"><i class="fa-regular fa-face-grin-beam"></i> <?=$Dic['approved']?></button></li>
    <li><button class="dropdown-item" type="button"><i class="fa-regular fa-face-frown"></i> <?=$Dic['denied']?></button></li>
  </ul>
</div>


            <?php
            }else{
            ?>
            <button addTrainee="<?=$d->id?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-user-plus"></i></button>
            <?php
            }
            ?>
        </div>
    </li>
<?php
    }
    }else{
?>
    <li class="list-group-item d-flex justify-content-center align-items-center" style="height:200px;">
        <div style="text-align:center;">
            <i class="fa-solid fa-face-meh" style="font-size:50px; color:#eee"></i>
            <h4 style="color:#eee;"><?=$Dic['No registered training']?></h4>
            <button newRegister class="btn btn-primary btn-sm"><?=$Dic['New Register']?></button>
        </div>
<?php
    }
?>
</ul>
<style>
    .divDialog{
        position:absolute;
        left:0;
        right:0;
        top:0;
        bottom:0;
        background:rgb(255,255,255, 0.8);
        z-index:10;
        justify-content:center!important;
        align-items:center!important;
        display:none;
    }
    .justify{
        width:60%;
    }
</style>
<div class="divDialog">
    <div class="card p-3 m-1 justify"></div>
</div>
<script>
    $(function(){
        Carregando('none');

        $("button[newRegister]").click(function(){
            Carregando();
            $.ajax({
                url:"src/trainings/form.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                },
                success:function(dados){
                    // $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                    $(".MenuRight").html(dados);
                }
            });
        });

        $("button[editTrainee]").click(function(){
            id = $(this).attr("editTrainee");
            Carregando();
            $.ajax({
                url:"src/trainings/form.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    id
                },
                success:function(dados){
                    // $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                    $(".MenuRight").html(dados);
                }
            });
        });

        $("button[addTrainee]").click(function(){
            trainee = $(this).attr("addTrainee");
            Carregando();
            $.ajax({
                url:"src/trainings/list.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    trainee,
                    action:'add'
                },
                success:function(dados){
                    console.log(dados)
                    $.ajax({
                        url:"src/trainings/add.php",
                        type:"POST",
                        data:{
                            training:'<?=$_POST['training']?>',
                            company:'<?=$_POST['company']?>',
                            treining_period:'<?=$_POST['treining_period']?>',
                        },
                        success:function(dados){
                            // $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                            $(".MenuRight").html(dados);


                            $.ajax({
                                url:"src/company/cycle_users.php",
                                type:"POST",
                                data:{
                                    training:'<?=$_POST['training']?>',
                                    company:'<?=$_POST['company']?>',
                                },
                                success:function(dados){
                                    $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                                }
                            })



                        }
                    });
                }
            });
        });




        $("button[payTrainee]").click(function(){
            id = $(this).attr("payTrainee");
            Carregando();
            $.ajax({
                url:"src/trainings/pay.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    id,
                },
                success:function(dados){
                    WindowPay = $.dialog({
                        title:'<?=$Dic['Payment confirmation']?>',
                        rtl: <?=(($_SESSION['lng'] == 'ar')?'true':'false')?>,
                        content:dados,
                        columnClass:'col-md-6'
                    });
                }
            });
        });



        $("button[actionRemoveTrainee]").click(function(){
            id = $(this).attr("actionRemoveTrainee");
            Carregando();
            $(".justify").html('');
            $.ajax({
                url:"src/trainings/justify_del.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    id,
                },
                success:function(dados){
                    $(".justify").html(dados);
                    $(".divDialog").css("display","flex");
                }
            });
        })

        $("button[removePayTrainee]").click(function(){
            id = $(this).attr("removePayTrainee");
            Carregando();
            $(".justify").html('');
            $.ajax({
                url:"src/trainings/pay_view.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    id,
                },
                success:function(dados){
                    $(".justify").html(dados);
                    $(".divDialog").css("display","flex");
                }
            });
        })



    })
</script>