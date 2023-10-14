<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");

        $query = "select
                            a.*,
                            b.name as company_name
                    from company_financial a
                    left join company b on a.company = b.id
                    where a.id = '{$_POST['id']}'";
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
                <?php
                if($d->cost_valcher){
                ?>
                    <div class="imageView"><object data="src/volume/financial_volcher/<?=$d->cost_valcher?>" style="max-width:100%;"></object></div>
                <?php
                }
                ?>

                <!-- <div class="col">
                    <div class="input-group">
                        <button class="btn btn-primary confirmPay w-100" type="button">
                            <i class="fa-solid fa-clipboard-check"></i> <?=$Dic['Confirm Payment']?>
                        </button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');

    })
</script>