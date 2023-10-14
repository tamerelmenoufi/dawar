<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        <?=(($_SESSION['lng'] == 'ar')?'right':'left')?>:60px;
        top:8px;
        z-index:0;
    }
    .search-clear{
        position:absolute;
        z-index:1;
        <?=(($_SESSION['lng'] == 'ar')?'left:80px;':'right:100px')?>;
        top:12px;
        color:red;
        cursor:pointer;
        opacity:0;
    }
</style>
<h4 class="Titulo<?=$md5?>"><?=$Dic['User Registration']?></h4>

<div class="row">
    <div class="col">

        <div class="input-group mb-3" style="position:relative;">
            <i class="fa-regular fa-circle-xmark search-clear"></i>
            <input type="text" class="form-control" id="search" name="search" placeholder="<?=$Dic['Search']?>" value="">
            <button class="btn btn-outline-secondary" type="button" id="search-action"><i class="fa-solid fa-magnifying-glass"></i> <?=$Dic['Search']?></button>
        </div>
        <div class="ListTrainings"></div>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');

        List = (search='')=>{

            if(search){
                $(".search-clear").css("opacity",1);
            }else{
                $(".search-clear").css("opacity",0);
            }
            Carregando();
            $.ajax({
                url:"src/trainings/list.php",
                type:"POST",
                data:{
                    search,
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period:'<?=$_POST['treining_period']?>',
                },
                success:function(dados){
                    $(".ListTrainings").html(dados);
                }
            })
        }

        List();

        $('#search-action').click(function (e) {
            search = $("#search").val();
            $(".ListTrainings").html('');
            List(search);
        })

        $('.search-clear').click(function (e) {
            search = '';
            $("#search").val('');
            $(".ListTrainings").html('');
            List(search);
        })

    })
</script>