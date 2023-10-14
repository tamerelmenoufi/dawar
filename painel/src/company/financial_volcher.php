<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

        $query = "select
                            a.*,
                            b.name as company_name
                    from company_financial a
                    left join company b on a.company = b.id
                    where b.id = '{$_POST['id']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $period = json_decode($d->period);
        $initial = $period[0];
        $final = $period[count($period) - 1];
        list($yi, $mi, $di) = explode("-",$initial);
        list($yf, $mf, $df) = explode("-",$final);
        $initial = mktime(0,0,0, $mi, $di, $yi);
        $final = mktime(23,59,59, $mf+1, $df, $yf);

        $period = date("d/m/Y", $initial)." - ".date("d/m/Y", $final);

        $token = false;
        for($i=0;$i<6;$i++){
            $token = $token . rand(0,9);
        }
        $_SESSION['token'] = $token;

        EnviarWappNovo($_SESSION['ProjectPainel']->phone, $Dic['TCV Inform: Your payment activation code is'].' '.$_SESSION['token']);

?>

<div class="col mt-4">
    <div class="mb-3">

        <div class="card p-3">
            <div class="row">
                <div class="col-1"><i class="fa-regular fa-building"></i></div>
                <div class="col-11"><?=$d->company_name?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-calendar-week"></i></div>
                <div class="col-11"><?=$period?></div>
            </div>
            <div class="row">
                <div class="col-1"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="col-11"> LE <?=$d->cost?></div>
            </div>
            <div class="mt-3">
                    <div class="input-group mb-1">
                        <button class="btn btn-secondary w-100" type="button">
                            <input type="file" class="attachment" target="logo" accept="image/*,application/pdf">
                            <input attachment<?=$md5?> type="hidden" id="logo" base64_image="" name_image="" type_image="" atual_image="<?=$d->logo?>">
                            <i class="fa-solid fa-receipt"></i> <?=$Dic['Attach Invoice']?>
                        </button>
                    </div>


                    <div class="row mt-3">
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
                    </div>
                    <div class="row mt-2 mb-3">
                        <div class="col">
                            <center style="color:#a1a1a1; font-size:12px;"><?=$Dic['Enter the code sent to your WhatsApp number using the keyboard.']?></center>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <input type="text" id="key" readonly class="form-control" style="font-size:20px; font-weight:bold; text-align:center;" />
                        </div>
                    </div>



                    <div class="col">
                        <div class="input-group">
                            <button class="btn btn-primary confirmPay w-100" type="button">
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


        if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {
                target = $(this).attr("target");
                if ($(this).val()) {
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {

                            var Base64 = f.target.result;
                            var type = file.type;
                            var name = file.name;

                            $(`#${target}`).attr("base64_image", Base64);
                            $(`#${target}`).attr("type_image", type);
                            $(`#${target}`).attr("name_image", name);
                            $(`#${target}`).parent("button").parent("div").parent("div").children(".imageView").remove();
                            $(`#${target}`).parent("button").parent("div").parent("div").prepend(`<div class="imageView"><object data="${Base64}" style="max-width:100%;"></object></div>`);

                            };
                            fileReader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            });
        } else {
            alert('Nao suporta HTML5');
        }


        $(".confirmPay").click(function(){
            base64 = $("input[attachment<?=$md5?>]").attr("base64_image");
            name = $("input[attachment<?=$md5?>]").attr("name_image");
            type = $("input[attachment<?=$md5?>]").attr("type_image");
            key = $("#key").val();
            id = '<?=$_POST['id']?>';
            pay = '<?=$_POST['pay']?>';

            // console.log(`Base:${base64} && Name:${name} && Type:${type} && Key:${key} && Id:${id}`)

            if(base64 && name && type && key && id && pay){
                console.log(key + ' - <?=$_SESSION['key']?> - ' + key.length)
                if(key.length != 6){

                    $.alert({
                        content:'<?=$Dic['The code must contain six numbers']?>',
                        title:false,
                        buttons:{
                            '<?=$Dic['ok']?>':function(){

                            }
                        }
                    });
                    // WindowPay.close();
                    return;

                }else if(key != '<?=$_SESSION['token']?>'){

                    $.alert({
                        content:'<?=$Dic['The code entered does not match']?>',
                        title:false,
                        buttons:{
                            '<?=$Dic['ok']?>':function(){

                            }
                        }
                    });

                    // WindowPay.close();
                    return;

                }
                Carregando();
                $.confirm({
                    content:'<?=$Dic['Do you confirm the payment?']?>',
                    title:false,
                    buttons:{
                        '<?=$Dic['Yes']?>':function(){
                            $.ajax({
                                url:"src/company/financial.php",
                                type:"POST",
                                data:{
                                    base64,
                                    name,
                                    type,
                                    key,
                                    id,
                                    pay
                                },
                                success:function(dados){
                                    $("#pageHome").html(dados);
                                    Carregando('none');
                                    dialogPay.close();
                                }
                            });
                        },
                        '<?=$Dic['No']?>':function(){

                        }
                    }
                });

            }else{
                $.alert({
                    content:'<?=$Dic['Please provide the requested data!']?><br>- <?=$Dic['Enter the code sent to your WhatsApp number using the keyboard.']?><br>- <?=$Dic['Attach Invoice']?>',
                    title:false,
                    buttons:{
                        '<?=$Dic['ok']?>':function(){

                        }
                    }
                });
            }

        });

        $("button[keyboard]").click(function(){
            opc = $(this).val();
            key = $("#key").val();
            if(opc == 'del'){
                $("#key").val('');
            }else{
                $("#key").val(key + opc);
            }

        })
    })
</script>