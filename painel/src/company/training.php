<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");

        if($_POST['delete']){
            $query = "delete from company_training where id = '{$_POST['delete']}'";
            mysqli_query($con, $query);
          }

          if($_POST['status']){
            $query = "update company_training set status = '{$_POST['opt']}' where id = '{$_POST['status']}'";
            mysqli_query($con, $query);
            exit();
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
            <h1><?=$Dic['Training']?></h1>
            <div class="col">
                <div class="companyHeader"></div>
            </div>
        </div>


        <p><span update>List Apdate</span></p>

        <div class="row">
            <div class="col">
                <div class="card p-3">

                    <div style="display:flex; justify-content:end">
                        <button
                            newRegister
                            class="btn btn-success"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasRight"
                            role="button"
                            aria-controls="offcanvasRight"
                        ><?=$Dic['New']?></button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                            <!-- <th scope="col"><?=$Dic['Company']?></th> -->
                            <th scope="col"><?=$Dic['Category']?></th>
                            <th scope="col"><?=$Dic['Training']?></th>
                            <th scope="col"><?=$Dic['Status']?></th>
                            <th scope="col" class="text-end"><?=$Dic['Actions']?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "select a.*,
                                             b.category as category_name,
                                             c.name as company_name
                                        from company_training a
                                        left join categories b on a.category = b.id
                                        left join company c on a.company = c.id
                                    where a.company = '{$_POST['id']}'
                                    order by a.name asc";
                            $result = mysqli_query($con, $query);
                            while($d = mysqli_fetch_object($result)){
                            ?>
                            <tr>
                            <!-- <td class="text-truncate"><?=$d->company_name?></td> -->
                            <td class="text-truncate"><?=$d->category_name?></td>
                            <td class="text-truncate"><?=$d->name?></td>
                            <td>

                            <div class="form-check form-switch">
                                <input class="form-check-input status" type="checkbox" <?=(($d->status)?'checked':false)?> user="<?=$d->id?>">
                            </div>

                            </td>
                            <td class="text-end text-nowrap">

                                <button
                                class="btn btn-primary"
                                style="margin-bottom:1px"
                                edit="<?=$d->id?>"
                                data-bs-toggle="offcanvas"
                                href="#offcanvasRight"
                                role="button"
                                aria-controls="offcanvasRight"
                                >
                                <?=$Dic['Edit']?>
                                </button>

                                <button class="btn btn-danger" delete="<?=$d->id?>">
                                <?=$Dic['Delete']?>
                                </button>

                            </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>



    </div>
</div>

<script>
    $(function(){

        Carregando('none');

        $("#category").select2({
                theme: "bootstrap-5",
                // selectionCssClass: "select2--small",
                // dropdownCssClass: "select2--small",
            });


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



        $("button[newRegister]").click(function(){
            $.ajax({
                url:"src/company/training_form.php",
                type:"POST",
                data:{
                    company:'<?=$_POST['id']?>',
                },
                success:function(dados){
                    $(".MenuRight").html(dados);
                }
            })
        })

        $("button[edit]").click(function(){
            id = $(this).attr("edit");
            $.ajax({
                url:"src/company/training_form.php",
                type:"POST",
                data:{
                  id,
                  company:'<?=$_POST['id']?>',
                },
                success:function(dados){
                    $(".MenuRight").html(dados);
                }
            })
        })

        $("button[delete]").click(function(){
            del = $(this).attr("delete");
            $.confirm({
                content:"<?=$Dic['Do you really want to delete the record?']?>",
                title:false,
                buttons:{
                    '<?=$Dic['Yes']?>':function(){
                        $.ajax({
                            url:"src/company/training.php",
                            type:"POST",
                            data:{
                                delete:del,
                                company:'<?=$_POST['id']?>',
                            },
                            success:function(dados){
                              // $.alert(dados);
                              $("#pageHome").html(dados);
                            }
                        })
                    },
                    '<?=$Dic['No']?>':function(){

                    }
                }
            });

        })


        $(".status").change(function(){

            status = $(this).attr("user");
            opt = false;

            if($(this).prop("checked") == true){
              opt = '1';
            }else{
              opt = '0';
            }


            $.ajax({
                url:"src/company/training.php",
                type:"POST",
                data:{
                    status,
                    opt
                },
                success:function(dados){
                    // $("#pageHome").html(dados);
                }
            })

        });







        //Remover após a homohlogação

        $(document).off('click').on('click', ".retorno", function(){
          $.ajax({
              url:"src/company/index.php",
              success:function(dados){
                $("#pageHome").html(dados);
              }
          })
        })



        $("span[update]").click(function(){
            Carregando();
            $.ajax({
                url:`src/company/training.php`,
                type:"POST",
                data:{
                    id:'<?=$_POST['id']?>'
                },
                success:function(dados){
                    $("#pageHome").html(dados);
                    Carregando('none');
                }
            })
        })

    })
</script>