<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");

        if($_POST['action']  == 'financial'){

            $query = "insert into company_financial set
                                company = '{$_POST['id']}',
                                cost = '{$_POST['cost']}',
                                period = '".json_encode($_POST['period'])."'";
            mysqli_query($con, $query);
        }

        if($_POST['del']){
            $query = "UPDATE company_financial set del = '1' where id = '{$_POST['del']}'";
            mysqli_query($con, $query);
        }

        if($_POST['pay']){

            $local = "../volume/financial_volcher";
            if(!is_dir("../volume/financial_volcher")) mkdir("../volume/financial_volcher");
            if(!is_dir($local)) mkdir($local);
            $img = explode("base64,", $_POST['base64']);
            $ext = substr($_POST['name'],strripos($_POST['name'], '.'),strlen($_POST['name']));
            $financial_volcher = md5($_POST['pay']).$ext;
            file_put_contents("{$local}/{$financial_volcher}", base64_decode($img[1]));

            $query = "UPDATE company_financial set
                                                payday = NOW(),
                                                cost_valcher = '{$financial_volcher}',
                                                user = '{$_SESSION['ProjectPainel']->id}'
                        where id = '{$_POST['pay']}'";
            mysqli_query($con, $query);
        }

        function VerifyPeriod($period, $date){
            list($ms, $ys) = explode("/",$date);
            $r = false;
            foreach($period as $ind => $val){
                list($y, $m, $d) = explode("-", $val);
                if(($y == $ys) and ($m == $ms)){
                    $r = true;
                }
            }
            return $r;
        }

        function VerifyPay($period, $date){
            global $ids;
            list($ms, $ys) = explode("/",$date);
            $r = false;
            foreach($period as $ind => $val){
                list($y, $m, $d) = explode("-", $val);
                if(($y == $ys) and ($m == $ms)){
                    $r = $ids[$date];
                }
            }
            return $r;
        }

        function VerifyID($period, $date){
            global $ID;
            list($ms, $ys) = explode("/",$date);
            $r = false;
            foreach($period as $ind => $val){
                list($y, $m, $d) = explode("-", $val);
                if(($y == $ys) and ($m == $ms)){
                    $r = $ID[$date];
                }
            }
            return $r;
        }

        $query = "select * from company where id = '{$_POST['id']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        //Limpeza nas parcelas canceladas
        $query = "select * from company_financial where company = '{$d->id}' and payday = 0";
        $result = mysqli_query($con, $query);
        while($dt = mysqli_fetch_object($result)){
            $dates = json_decode($dt->period);
            $today = strtotime(date("Y-m-d"));
            foreach($dates as $ind => $dts){
                if(strtotime($dts) < $today){
                    mysqli_query($con, "update company_financial set del = '1' where id = '{$dt->id}'");
                }
            }
        }
        //FIm da limpeza das parcelas canceladas

        $q = "select *, if(payday > 0, 1, 0) as status from company_financial where company = '{$d->id}' and del != '1'";
        $r = mysqli_query($con, $q);
        $period = [];
        $ids = [];
        $ID = [];

        while($p = mysqli_fetch_object($r)){

            foreach(json_decode($p->period) as $i => $prd){
                array_push($period, $prd);
                list($prdy,$prdm,$prdd) = explode("-",$prd);
                $ids["{$prdm}/{$prdy}"] = $p->status;
                $ID["{$prdm}/{$prdy}"] = $p->id;
            }

        }

?>
<style>
    .BorderData{
        border:1px #dee2e6 solid;
        border-top:0;
        padding:15px;
    }
</style>


<div class="col">
    <div class="m-3">
        <div class="row">
            <h1><?=$Dic['Financial']?></h1>
            <div class="col">
                <div class="companyHeader"></div>
            </div>
        </div>




        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <?=$Dic['Acquire Credits']?>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-1"><?=$Dic['Period authorized for registration']?></h5>
                        <ul class="list-group">
                            <?php
                            $toDay = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
                            $toLimit = mktime(date("H"),date("i"),date("s"),date("m")+6,date("d"),date("Y"));
                            // $period = [];
                            for($i=0; $i<6; $i++){
                                $nextDay = mktime(date("H"),date("i"),date("s"),date("m")+$i,date("d"),date("Y"));
                                $date = date("m/Y", $nextDay);
                            ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <?php
                                        if(VerifyPay($period, $date)){
                                    ?>
                                    <i class="fa-solid fa-check-double" style="color:green"></i>
                                    <?php
                                        }else{
                                    ?>
                                    <input class="form-check-input invoiceAdd" type="checkbox" value="1000" <?=((VerifyPeriod($period, $date))?'disabled checked':false)?> period="<?=date("Y-m-d", $nextDay)?>" id="opction<?=$i?>">
                                    <?php
                                        }
                                    ?>
                                    <label class="form-check-label" for="opction<?=$i?>"><?=$date?></label>
                                </div>
                                <span <?=((VerifyID($period, $date))?'valcher="'.VerifyID($period, $date).'" style="cursor:pointer;"':false)?> class="badge bg-<?=((VerifyPay($period, $date))?'success':'primary')?> rounded-pill">LE 1000.00</span>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="invoice mt-3" style="display:none; margin-top:20px;">
            <div class="row">
                <div class="col">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="padding-left:30px; padding-right:30px; ">
                            <label class="form-check-label" for="opction<?=$i?>"><b><?=$Dic['New Invoice']?></b></label>
                            <span class="badge bg-danger rounded-pill toalInvoce" style="cursor:pointer;"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-3" style="margin-top:20px;">
            <div class="row">
                <div class="col">
                    <ul class="list-group">
                        <?php
                        $query = "select * from company_financial where company = '{$d->id}' and payday = 0 and del != '1'";
                        $result = mysqli_query($con, $query);
                        while($dt = mysqli_fetch_object($result)){
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="padding-left:30px; padding-right:30px; ">
                            <label class="form-check-label" for="opction<?=$i?>"><i delete="<?=$dt->id?>" class="fa-solid fa-trash-can" style="color:red; cursor:pointer;"></i> <b><?=$Dic['Pending Invoice']?></b></label>
                            <span pay="<?=$dt->id?>" class="badge bg-warning rounded-pill" style="cursor:pointer;"><?=$Dic['Pay']?>  LE <?=$dt->cost?></span>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>



    </div>
</div>

<script>
    $(function(){

        $.ajax({
            url:"src/company/header.php",
            type:"POST",
            data:{
                id:'<?=$_POST['id']?>'
            },
            success:function(dados){
                $(".companyHeader").html(dados);
            }
        })

        $(document).off('click').on('click', ".retorno", function(){
          $.ajax({
              url:"src/company/index.php",
              success:function(dados){
                $("#pageHome").html(dados);
              }
          })
        })

        $(".invoiceAdd").click(function(){

            i = $(".invoiceAdd").index(this);
            // console.log("Index: "+ i);
            $(".invoice").css("display","none");
            value = $(this).val();
            total = 0;

            $(".invoiceAdd").each(function(ind){
                // console.log($(this).prop("checked"));
                if(ind != i && !$(this).attr("disabled")){
                    $(this).prop("checked", false);
                }
            });

            if($(this).prop("checked")){

                $(".invoiceAdd").each(function(ind){
                    // console.log($(this).prop("checked"));
                    if(ind <= i && !$(this).attr("disabled")){
                        $(this).prop("checked", true);
                    }
                });

            }else{

                $(".invoiceAdd").each(function(ind){
                    // console.log($(this).prop("checked"));
                    if(ind <= i && !$(this).attr("disabled")){
                        $(this).prop("checked", false);
                    }
                });
            }


            $(".invoiceAdd").each(function(ind){
                // console.log($(this).prop("checked"));

                if($(this).prop("checked") && !$(this).attr("disabled")){
                    $(".invoice").css("display","block");
                    total = (total*1 + value*1);
                    $(".toalInvoce").html(`<?=$Dic['Pay']?> LE ${total.toFixed(2)}`);
                }
            });


        })

        $(".toalInvoce").click(function(){
            period = [];
            cost = 0;
            $(".invoiceAdd").each(function(){
                // console.log($(this).prop("checked"));

                if($(this).prop("checked") == true && !$(this).attr("disabled")){
                    period.push($(this).attr("period"));
                    cost = (cost*1 + $(this).val()*1);
                }
            });

            Carregando();
            $.ajax({
                url:"src/company/financial.php",
                type:"POST",
                data:{
                    id:"<?=$d->id?>",
                    period,
                    cost,
                    action:'financial'
                },
                success:function(dados){
                    $("#pageHome").html(dados);
                    Carregando('none');
                }
            });

            // console.log(period);
        })

        $("span[pay]").click(function(){
            pay = $(this).attr("pay");

            $.confirm({
                content:"<?=$Dic['Confirm invoice payment?']?>",
                title:false,
                buttons:{
                    '<?=$Dic['No']?>':function(){

                    },
                    '<?=$Dic['Yes']?>':function(){

                        Carregando();
                        $.ajax({
                            url:"src/company/financial_volcher.php",
                            type:"POST",
                            data:{
                                pay,
                                id:'<?=$d->id?>'
                            },
                            success:function(dados){
                                dialogPay = $.dialog({
                                    content:dados,
                                    title:false,
                                    columnClass:'col-md-6'
                                });
                                // $("#pageHome").html(dados);
                                // Carregando('none');
                            }
                        });
                    }
                }
            });

        });

        $("i[delete]").click(function(){
            del = $(this).attr("delete");

            $.confirm({
                content:"<?=$Dic['Do you really want to delete the record?']?>",
                title:false,
                buttons:{
                    '<?=$Dic['No']?>':function(){

                    },
                    '<?=$Dic['Yes']?>':function(){
                        Carregando();
                        $.ajax({
                            url:"src/company/financial.php",
                            type:"POST",
                            data:{
                                del,
                                id:'<?=$d->id?>'
                            },
                            success:function(dados){
                                $("#pageHome").html(dados);
                                Carregando('none');
                            }
                        });
                    }
                }
            });

        })


        $("span[valcher]").click(function(){
            id = $(this).attr("valcher");
            Carregando();
            $.ajax({
                url:"src/company/volcher.php",
                type:"POST",
                data:{
                    id
                },
                success:function(dados){
                    dialogPay = $.dialog({
                        content:dados,
                        title:false,
                        columnClass:'col-md-6'
                    });
                    // $("#pageHome").html(dados);
                    // Carregando('none');
                }
            });
        })

    })
</script>