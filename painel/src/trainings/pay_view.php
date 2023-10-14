<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


        if($_POST['action'] == 'pay'){

            $local = "../volume/training_pay";
            if(!is_dir("../volume/training_pay")) mkdir("../volume/training_pay");
            if(!is_dir($local)) mkdir($local);
            $img = explode("base64,", $_POST['base64']);
            $ext = substr($_POST['name'],strripos($_POST['name'], '.'),strlen($_POST['name']));
            $voucher_pay = md5($_POST['id']).$ext;
            file_put_contents("{$local}/{$voucher_pay}", base64_decode($img[1]));

            $query = "update treinee_admission set
                            cost_pay = '1',
                            voucher_pay = '{$voucher_pay}',
                            date_pay = '".date("Y-m-d H:i:s")."',
                            user_pay = '{$_SESSION['ProjectPainel']->id}'
                        where id = '{$_POST['id']}'
                    ";
            mysqli_query($con, $query);

            exit();
        }


        $query = "select
                            a.*,
                            b.name as trainee_name,
                            b.phone as trainee_phone,
                            c.name as training_name,
                            d.name as company_name
                    from treinee_admission a
                    left join trainee b on a.trainee = b.id
                    left join company_training c on a.training = c.id
                    left join company d on c.company = d.id
                where a.id = '{$_POST['id']}' and a.del != '1'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $token = false;
        for($i=0;$i<6;$i++){
            $token = $token . rand(0,9);
        }
        $_SESSION['token'] = $token;

        // EnviarWappNovo($_SESSION['ProjectPainel']->phone, $Dic['TCV Inform: Your payment activation code is'].' '.$_SESSION['token']);

?>

<div style="text-align:right; margin-bottom:20px;">
    <i style="cursor:pointer" class="fa fa-close closeJustify"></i>
</div>

<div class="col">
    <div class="mb-3">

        <div class="card p-3">
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="col-11"><?=$d->trainee_name?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-book-open"></i></div>
                <div class="col-11"><?=$d->training_name?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-regular fa-building"></i></div>
                <div class="col-11"><?=$d->company_name?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-calendar-week"></i></div>
                <div class="col-11"><?=$d->initial_date?> - <?=$d->final_date?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="col-11"> LE <?=$d->cost?></div>
            </div>



            <div class="mt-3">
                <?php
                if($d->voucher_pay){
                ?>
                    <div class="imageView"><object data="src/volume/training_pay/<?=$d->voucher_pay?>" style="max-width:100%;"></object></div>
                <?php
                }
                ?>
                    <!-- <div class="input-group mb-1">
                        <button class="btn btn-secondary w-100" type="button">
                            <input type="file" class="attachment" target="logo" accept="image/*,application/pdf">
                            <input attachment<?=$md5?> type="hidden" id="logo" base64_image="" name_image="" type_image="" atual_image="<?=$d->logo?>">
                            <i class="fa-solid fa-receipt"></i> <?=$Dic['Attach Invoice']?>
                        </button>
                    </div> -->


                    <!-- <div class="row mt-3">
                        <?php
                        for($i=0;$i<10;$i++){
                        ?>
                        <div class="col-1">
                            <button keyboard class="btn btn-warning btn-sm" value="<?=$i?>"><?=$i?></button>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-2">
                            <button keyboard class="btn btn-danger btn-sm w-100" value="del">
                                <i class="fa-solid fa-delete-left"></i>
                            </button>
                        </div>
                    </div> -->
                    <!-- <div class="row mt-2 mb-3">
                        <div class="col">
                            <center style="color:#a1a1a1; font-size:12px;"><?=$Dic['Enter the code sent to your WhatsApp number using the keyboard.']?></center>
                        </div>
                    </div> -->
                    <!-- <div class="row mt-3 mb-3">
                        <div class="col">
                            <input type="text" id="key" class="form-control" style="font-size:20px; font-weight:bold; text-align:center;" />
                        </div>
                    </div> -->



                    <div class="col">
                        <div class="input-group">
                            <button actionRemoveTrainee<?=md5?>='<?=$d->id?>' class="btn btn-primary w-100" type="button">
                                <i class="fa-solid fa-clipboard-check"></i> <?=$Dic['Confirm Payment']?>
                            </button>
                        </div>
                    </div>
            </div>



        </div>


    </div>
</div>

<script>
    $(function(){
        Carregando('none');

        $(".closeJustify, .cancel").click(function(){
            $(".divDialog").css("display","none");
        })


        $("button[actionRemoveTrainee<?=md5?>]").click(function(){
            id = $(this).attr("actionRemoveTrainee<?=md5?>");
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


    })
</script>