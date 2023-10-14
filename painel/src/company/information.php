<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


        $query = "select * from company where id = '{$_POST['id']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

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
            <h1><?=$Dic['Registrations']?></h1>
            <div class="col">
                <div class="companyHeader"></div>
            </div>
        </div>


        <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#panel-tab-pane" type="button" role="tab" aria-controls="panel-tab-pane" aria-selected="false" target="registrations.php"><?=$Dic['Registrations']?></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#panel-tab-pane" type="button" role="tab" aria-controls="panel-tab-pane" aria-selected="false" target="calendar.php"><?=$Dic['Calendar']?></button>
            </li>
        </ul>
        <div class="tab-content BorderData" id="myTabContent">
            <div class="tab-pane fade show active" id="panel-tab-pane" role="tabpanel" aria-labelledby="panel-tab" tabindex="0">...</div>
        </div> -->



        <ul class="list-group">
            <?php
            $query = "select a.*, (select count(*) from treinee_admission where training = a.id and del != '1') as qt from company_training a where a.company = '{$_POST['id']}' and a.status = '1' order by a.name asc";
            $result = mysqli_query($con, $query);
            if(!mysqli_num_rows($result)){
            ?>
                <li style="height:250px;" class="list-group-item d-flex justify-content-center align-items-center">
                    <div style="color:#a1a1a1">
                        <h1 style="text-align:center;">
                            <i class="fa-regular fa-face-meh"></i>
                        </h1>
                        <p><?=$Dic['No registered training']?></p>
                    </div>
                </li>
            <?php
            }else{
                while($t = mysqli_fetch_object($result)){
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?=$t->name?>
                    <?php
                    if($t->qt){
                    ?>
                        <span class="badge bg-primary rounded-pill"><?=$t->qt?> <?=$Dic['Registered']?></span>
                    <?php
                    }else{
                    ?>
                        <span><i class="fa-regular fa-face-meh"></i> <?=$Dic['No registered interns']?></span>
                    <?php
                    }
                    ?>
                </li>
            <?php
                }
            }
            ?>
        </ul>


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

        $.ajax({
            url:`src/company/screens/registrations.php`,
            success:function(dados){
            $("#panel-tab-pane").html(dados);
            Carregando('none');
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

        $("button[target]").click(function(){
            target = $(this).attr("target");
            Carregando();
            $.ajax({
                url:`src/company/screens/${target}`,
                success:function(dados){
                    $("#panel-tab-pane").html(dados);
                    Carregando('none');
                }
            })
        })

    })
</script>