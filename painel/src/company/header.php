<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


        $query = "select * from company where id = '{$_POST['id']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

?>
<div style="position:absolute; top:70px; width:calc(100% - 40px);">
    <div class="row g-0">
        <div class="col-md-12 text-end pb-3">
            <button class="btn btn-light retorno"><i class="fa-solid fa-angles-left"></i> <?=$Dic['Back']?></button>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4" style="position:relative">
            <div style="border:solid 0px red; position:absolute; left:0; right:0; top:0; bottom:0; background-size:contain; background-image:url(./src/volume/<?=$d->id?>/<?=$d->logo?>); background-repeat:no-repeat; background-position:center center;"></div>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?=$d->name?></h5>
                <p class="card-text"><?=$d->responsible_name?></p>
                <p class="card-text"><small class="text-body-secondary"><?=$d->responsible_phone?></small></p>
            </div>
        </div>
    </div>
</div>