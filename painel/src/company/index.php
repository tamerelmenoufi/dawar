<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");

    if($_POST['delete']){
      $query = "delete from empresas where codigo = '{$_POST['delete']}'";
      mysqli_query($con, $query);
    }

    if($_POST['situacao']){
      $query = "update empresas set situacao = '{$_POST['opt']}' where codigo = '{$_POST['situacao']}'";
      mysqli_query($con, $query);
      exit();
    }
?>

<style>
  td{
    white-space: nowrap;
  }
</style>
<div class="col">
  <div class="m-3">


    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header"><?=$Dic['List of Companies']?></h5>
          <div class="card-body">
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

            <div class="table-responsive" style="min-height:300px;">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th scope="col"><?=$Dic['Name']?></th>
                    <th scope="col"><?=$Dic['Status']?></th>
                    <th scope="col" class="text-end"><?=$Dic['Actions']?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "select * from empresas order by nome asc";
                    $result = mysqli_query($con, $query);
                    while($d = mysqli_fetch_object($result)){
                  ?>
                  <tr>
                    <td class="w-100"><?=$d->nome?></td>
                    <td>

                    <div class="form-check form-switch">
                      <input class="form-check-input status" type="checkbox" <?=(($d->situacao)?'checked':false)?> user="<?=$d->codigo?>">
                    </div>

                    </td>
                    <td class="text-end">

                      <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                          <?=$Dic['Options']?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <!-- <li><button class="dropdown-item" type="button" target="information.php" identification="<?=$d->id?>"><?=$Dic['Information']?></button></li>
                          <li><button class="dropdown-item" type="button" target="cycle.php" identification="<?=$d->id?>"><?=$Dic['Cycles']?></button></li>
                          <li><button class="dropdown-item" type="button" target="training.php" identification="<?=$d->id?>"><?=$Dic['Training']?></button></li>
                          <li><button class="dropdown-item" type="button" target="financial.php" identification="<?=$d->id?>"><?=$Dic['Financial']?></button></li> -->
                        </ul>
                      </div>

                      <button
                        class="btn btn-primary"
                        style="margin-bottom:1px"
                        edit="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasRight"
                        role="button"
                        aria-controls="offcanvasRight"
                      >
                      <?=$Dic['Edit']?>
                      </button>

                      <button class="btn btn-danger" delete="<?=$d->codigo?>">
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
</div>


<script>
    $(function(){
        Carregando('none');
        $("button[newRegister]").click(function(){
            $.ajax({
                url:"src/company/form.php",
                success:function(dados){
                    $(".MenuRight").html(dados);
                }
            })
        })

        $("button[edit]").click(function(){
            codigo = $(this).attr("edit");
            $.ajax({
                url:"src/company/form.php",
                type:"POST",
                data:{
                  codigo
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
                            url:"src/company/index.php",
                            type:"POST",
                            data:{
                                delete:del
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

            situacao = $(this).attr("user");
            opt = false;

            if($(this).prop("checked") == true){
              opt = '1';
            }else{
              opt = '0';
            }


            $.ajax({
                url:"src/company/index.php",
                type:"POST",
                data:{
                    situacao,
                    opt
                },
                success:function(dados){
                    // $("#pageHome").html(dados);
                }
            })

        });


    })
</script>