<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");


    $query = "SELECT
                    (select count(*) from company_training) as training,
                    (select count(*) from company) as company,
                    (select count(*) from trainee) as trainee,
                    (select count(*) from treinee_admission) as admission
            ";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


?>


<div class="row g-0">
    <div class="col-md-3">
        <div class="alert alert-success text-center m-2" role="alert">
            <small><?=$Dic['Company']?></small>
            <h1><?=$d->company?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-danger text-center m-2" role="alert">
            <small><?=$Dic['Training']?></small>
            <h1><?=$d->training?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-warning text-center m-2" role="alert">
            <small><?=$Dic['Trainees']?></small>
            <h1><?=$d->trainee?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-info text-center m-2" role="alert">
            <small><?=$Dic['Admission']?></small>
            <h1><?=$d->admission?></h1>
        </div>
    </div>
</div>


<div class="row g-0">
    <div class="col-md-6 p-2">
        <div class="card p-3">
            <h3><?=$Dic['Registered Training']?></h3>
            <table>
            <?php
                $query = "select count(*) as qt, b.name as company_name, (select count(*) from company_training) as total from company_training a left join company b on a.company = b.id group by a.company order by qt desc";
                $result = mysqli_query($con, $query);
                while($d = mysqli_fetch_object($result)){
            ?>
            <tr>
                <td>
                    <div><small><?=$d->company_name?> (<?=$d->qt?>)</small></div>
                    <div style="height:25px; border-radius:5px; background-color:#eee; width:100%">
                        <div style="height:25px; padding:3px; border-radius:5px; margin-bottom:10px; background-color:green; width:<?=number_format($d->qt*100/$d->total,0,false,false)?>%; text-align:center; color:#fff; font-size:12px;"><?=number_format($d->qt*100/$d->total,0,false,false)?>%</div>
                    </div>
                </td>
            </tr>
            <?php
                }

            ?>
            </table>
        </div>
    </div>
    <div class="col-md-6 p-2">
        <div class="card p-3">
            <h3><?=$Dic['Registered Trainee']?></h3>
            <table>
            <?php
                $query = "select count(*) as qt, b.name as company_name, (select count(*) from treinee_admission) as total from treinee_admission a left join company b on a.company = b.id group by a.company order by qt desc";
                $result = mysqli_query($con, $query);
                while($d = mysqli_fetch_object($result)){
            ?>
            <tr>
                <td>
                    <div><small><?=$d->company_name?> (<?=$d->qt?>)</small></div>
                    <div style="height:25px; border-radius:5px; background-color:#eee; width:100%">
                        <div style="height:25px; padding:3px; border-radius:5px; margin-bottom:10px; background-color:green; width:<?=number_format($d->qt*100/$d->total,0,false,false)?>%; text-align:center; color:#fff; font-size:12px;"><?=number_format($d->qt*100/$d->total,0,false,false)?>%</div>
                    </div>
                </td>
            </tr>
            <?php
                }

            ?>
            </table>
        </div>
    </div>
</div>


<script>
    $(function(){

        Carregando('none');


    })
</script>