<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_POST['delete']){
      $query = "delete from categories where id = '{$_POST['delete']}'";
      mysqli_query($con, $query);
    }

    if($_POST['status']){
      $query = "update categories set status = '{$_POST['opt']}' where id = '{$_POST['status']}'";
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
          <h5 class="card-header"><?=$Dic['Categories List']?></h5>
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

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col"><?=$Dic['Category']?></th>
                  <th scope="col"><?=$Dic['Status']?></th>
                  <th scope="col" class="text-end"><?=$Dic['Actions']?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select * from categories order by category asc";
                  $result = mysqli_query($con, $query);
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td class="w-100"><?=$d->category?></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input status" type="checkbox" <?=(($d->status)?'checked':false)?> category="<?=$d->id?>">
                    </div>
                  </td>
                  <td class="text-end">
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
</div>


<script>
    $(function(){
        Carregando('none');
        $("button[newRegister]").click(function(){
            $.ajax({
                url:"src/categories/form.php",
                success:function(dados){
                    $(".MenuRight").html(dados);
                }
            })
        })

        $("button[edit]").click(function(){
            id = $(this).attr("edit");
            $.ajax({
                url:"src/categories/form.php",
                type:"POST",
                data:{
                  id
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
                            url:"src/categories/index.php",
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

          status = $(this).attr("category");
          opt = false;

          if($(this).prop("checked") == true){
            opt = '1';
          }else{
            opt = '0';
          }


          $.ajax({
              url:"src/categories/index.php",
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


    })
</script>