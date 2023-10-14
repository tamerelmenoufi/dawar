<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");


    if($_POST['action'] == 'save'){

        $data = $_POST;
        $attr = [];

        unset($data['id']);
        unset($data['action']);


        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . addslashes($value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['id']){
            $query = "update company_training set {$attr} where id = '{$_POST['id']}'";
            $exec = mysqli_query($con, $query);
            $id = $_POST['id'];
        }else{
            $query = "insert into company_training set registration_date = NOW(), {$attr}";
            $exec = mysqli_query($con, $query);
            $id = mysqli_insert_id($con);
        }


        $return = [
            'status' => true,
            'id' => $id." - ".$query,
        ];

        echo json_encode($return);

        exit();
    }


    $query = "select * from company_training where id = '{$_POST['id']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }

</style>
<h4 class="Titulo<?=$md5?>"><?=$Dic['Training Registration']?></h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">

                <div class="form-floating mb-3">
                    <select SearchFild name="category" class="form-control" id="category">
                        <option value="" >:: <?=$Dic['Category']?> ::</option>
                        <?php
                        $q = "select * from categories where status = '1' order by category";
                        $r = mysqli_query($con, $q);
                        while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->id?>" <?=(($s->id == $d->category)?'selected':false)?>><?=$s->category?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="category"><?=$Dic['Category']?></label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?=$Dic['Training Name']?>" value="<?=$d->name?>">
                    <label for="name"><?=$Dic['Training Name']?>*</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" id="training_description" name="training_description" placeholder="<?=$Dic['Training Description']?>" style="height:200px;"><?=$d->training_description?></textarea>
                    <label for="training_description" ><?=$Dic['Training Description']?>*</label>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><?=$Dic['Cost of training']?> LE</span>
                    <input type="number" id="cost" name="cost" class="form-control" value="<?=$d->cost?>">
                    <span class="input-group-text">.00</span>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><?=$Dic['Number of trainings']?>&nbsp;<i class="fa-solid fa-users-viewfinder"></i></span>
                    <input type="number" id="trainings" name="trainings" class="form-control" value="<?=$d->trainings?>">
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="responsible_training" name="responsible_training" placeholder="<?=$Dic['Responsible for Training']?>" value="<?=$d->responsible_training?>">
                    <label for="responsible_training"><?=$Dic['Responsible for Training']?>*</label>
                </div>


                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="responsible_phone" name="responsible_phone" placeholder="<?=$Dic['Responsible Phone']?>" value="<?=$d->responsible_phone?>">
                    <label for="responsible_phone"><?=$Dic['Responsible Phone']?>*</label>
                </div>


                <div class="form-floating mb-3">
                    <select name="status" class="form-control" id="status">
                        <option value="1" <?=(($d->status == '1')?'selected':false)?>><?=$Dic['Allowed']?></option>
                        <option value="0" <?=(($d->status == '0')?'selected':false)?>><?=$Dic['Blocked']?></option>
                    </select>
                    <label for="email"><?=$Dic['Status']?></label>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button type="submit" class="btn btn-success btn-ms"><?=$Dic['Save']?></button>
                    <input type="hidden" id="id" value="<?=$_POST['id']?>" />
                    <input type="hidden" id="company" name="company" value="<?=$_POST['company']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("select[SearchFild]").select2({
                theme: "bootstrap-5",
                dropdownParent: $(".MenuRight"),
                // selectionCssClass: "select2--small",
                // dropdownCssClass: "select2--small",
            });


            $("#responsible_phone").mask("299999999999");

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var id = $('#id').val();
                var filds = $(this).serializeArray();

                if (id) {
                    filds.push({name: 'id', value: id})
                }

                filds.push({name: 'action', value: 'save'})


                Carregando();

                $.ajax({
                    url:"src/company/training_form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: filds,
                    success:function(dados){
                        console.log(dados);
                        // if(dados.status){
                            $.ajax({
                                url:"src/company/training.php",
                                type:"POST",
                                data:{
                                    id:"<?=$_POST['company']?>"
                                },
                                success:function(dados){
                                    $("#pageHome").html(dados);
                                    let myOffCanvas = document.getElementById('offcanvasRight');
                                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                                    openedCanvas.hide();
                                }
                            });
                        // }
                    },
                    error:function(erro){

                        // $.alert('Ocorreu um erro!' + erro.toString());
                        //dados de teste
                    }
                });

            });

        })
    </script>