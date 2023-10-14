<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

        $query = "SELECT * FROM `company_financial`
                    where company = '{$_POST['id']}' and del != '1' and payday > 0 and
                        (
                            (DATE_ADD(period->>'$[0]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."') or
                            (DATE_ADD(period->>'$[1]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."') or
                            (DATE_ADD(period->>'$[2]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."') or
                            (DATE_ADD(period->>'$[3]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."') or
                            (DATE_ADD(period->>'$[4]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."') or
                            (DATE_ADD(period->>'$[5]', INTERVAL 1 MONTH) >= '".date("Y-m-d")."')
                        )
        ";
        $result = mysqli_query($con, $query);
        $n = mysqli_num_rows($result);

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
            <h1><?=$Dic['Cycles']?></h1>
            <div class="col">
                <div class="companyHeader"></div>
            </div>
        </div>



        <ul class="list-group">
            <?php
            if(!$n){
            ?>
                <li style="height:250px;" class="list-group-item d-flex justify-content-center align-items-center">
                    <div style="color:#a1a1a1">
                        <h1 style="text-align:center;">
                            <i class="fa-regular fa-face-meh"></i>
                        </h1>
                        <p><?=$Dic['Company does not have credit for registrations']?></p>
                    </div>
                </li>
            <?php
            }else{
                $query = "select * from company_training a where company = '{$_POST['id']}' and status = '1' order by name asc";
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
            ?>
            <div class="accordion" id="accordionExample">
            <?php
                    while($t = mysqli_fetch_object($result)){
            ?>


                <div class="accordion-item mb-3" style="border-top:1px solid #e9ecef">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?=$t->id?>" aria-expanded="false" aria-controls="collapseOne<?=$t->id?>">
                            <b><?=$t->name?></b>
                        </button>
                    </h2>
                    <div id="collapseOne<?=$t->id?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div
                            cycleUsers="<?=$t->id?>"
                            cycleCost="<?=$t->cost?>"
                            cycleTrainings="<?=$t->trainings?>"
                            class="accordion-body"
                        ></div>
                    </div>
                </div>

            <?php
                    }
            ?>
            </div>
            <?php
                }
            }
            ?>
        </ul>




    </div>
</div>

<script>
    $(function(){

        Carregando('none');

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


        function Target(target,company,training,cycleTrainings,cycleCost){
            $.ajax({
                url:"src/company/cycle_users.php",
                type:"POST",
                data:{
                    training,
                    company,
                    cycleTrainings,
                    cycleCost
                },
                success:function(dados){
                    target.html(dados);
                }
            })
        }

        $("div[cycleUsers]").each(function(){
            company = '<?=$_POST['id']?>';
            training = $(this).attr("cycleUsers");
            target = $(this);
            cycleTrainings = $(this).attr('cycleTrainings');
            cycleCost = $(this).attr('cycleCost');
            Target(target,company,training,cycleTrainings,cycleCost);
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