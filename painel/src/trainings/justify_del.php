<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");


    $token = false;
    for($i=0;$i<6;$i++){
        $token = $token . rand(0,9);
    }
    $_SESSION['token'] = $token;

    EnviarWappNovo($_SESSION['ProjectPainel']->phone, $Dic['TCV Inform: Your code to unsubscribe is'].' '.$_SESSION['token']);


?>

<div style="text-align:right; margin-bottom:20px;">
    <i style="cursor:pointer" class="fa fa-close closeJustify"></i>
</div>
<p><b><?=$Dic['Enter the reason for unsubscribing']?></b></p>
<label for="justify mt-2"><?=$Dic['Justify']?></label>
<textarea type="text" id="justify" class="form-control"></textarea>
<label for="justify_key mt-2"><?=$Dic['Key']?></label>
<input type="text" id="justify_key" class="form-control" />
<button removeTrainee class="btn btn-danger btn-sm mt-2"><?=$Dic['Delete']?></button>
<button class="btn btn-secondary btn-sm mt-2 cancel"><?=$Dic['Cancel']?></button>

<script>
    $(function(){
        Carregando('none');
        $(".closeJustify, .cancel").click(function(){
            $(".divDialog").css("display","none");
        })


        $("button[removeTrainee]").click(function(){
            justify = $("#justify").val();
            key = $("#justify_key").val();
            if(!justify || !key){
                $.alert({
                    content:'<?=$Dic['Please justify the cancellation of registration and inform the authorization key sent to your registered WhatsApp.']?>',
                    title:false,
                    buttons:{
                        '<?=$Dic['ok']?>':function(){

                        }
                    }
                })
                return;
            }
            Carregando();
            $(".divDialog").css("display","none");
            $.ajax({
                url:"src/trainings/list.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                    id:'<?=$_POST['id']?>',
                    justify,
                    key,
                    action:'delete'
                },
                success:function(dados){
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


    })
</script>