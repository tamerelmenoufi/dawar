<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


    if($_POST['action'] == 'save'){

        $data = $_POST;
        $attr = [];

        unset($data['id']);
        unset($data['action']);

        $base64Image = [];
        $nameImage = [];
        $typeImage = [];
        $atualImage = [];

        foreach ($data as $name => $value) {

            $verify = explode("-",$name);
            if($verify[0] == 'image'){
                if($verify[2] == 'base64_image'){
                    $base64Image[$verify[1]] = $value;
                }else if($verify[2] == 'name_image' and $value){
                    $ext = substr($value,strripos($value, '.'),strlen($value));
                    $nameImage[$verify[1]] = md5($value.$verify[1].date("YmdHis")).$ext;
                    $attr[] = "{$verify[1]} = '" . $nameImage[$verify[1]] . "'";
                }else if($verify[2] == 'atual_image' and $base64Image[$verify[1]] and $nameImage[$verify[1]] and $value){
                    $atualImage[$verify[1]] = $value;
                }
            }else{
                $attr[] = "{$name} = '" . addslashes($value) . "'";
            }
        }

        $attr = implode(', ', $attr);

        if($_POST['id']){
            $query = "update company set {$attr} where id = '{$_POST['id']}'";
            $exec = mysqli_query($con, $query);
            $id = $_POST['id'];
        }else{
            $query = "insert into company set registration_date = NOW(), {$attr}";
            $exec = mysqli_query($con, $query);
            $id = mysqli_insert_id($con);
        }

        if($exec and $base64Image){
            $local = "../volume/{$id}";
            if(!is_dir($local)) mkdir($local);
            foreach($base64Image as $ind => $img){
                $img = explode("base64,", $img);
                file_put_contents("{$local}/{$nameImage[$ind]}", base64_decode($img[1]));
                if(is_file("{$local}/{$atualImage[$ind]}")) unlink("{$local}/{$atualImage[$ind]}");
            }
        }

        $return = [
            'status' => true,
            'id' => $id." - ".$query
        ];

        echo json_encode($return);

        exit();
    }


    $query = "select * from company where id = '{$_POST['id']}'";
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
<h4 class="Titulo<?=$md5?>"><?=$Dic['Company Registration']?></h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">

                <div class="form-floating mb-3">
                    <select name="category" class="form-control" id="category">
                        <option value="" >::<?=$Dic['Selection']?>::</option>
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

                <!-- <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?=$Dic['Full Name of Company']?>" value="<?=$d->name?>">
                    <label for="nome"><?=$Dic['Name']?>*</label>
                </div> -->

                <div class="input-group mb-3">
                    <?php
                    if($d->logo){
                    ?>
                    <div class="imageView"><div><i class="fa-solid fa-trash"></i></div><object data="src/volume/<?=$d->id?>/<?=$d->logo?>"></object></div>
                    <?php
                    }
                    ?>
                    <div class="form-floating" style="width:calc(100% - 50px);">
                        <input type="text" name="name" id="name" class="form-control form-text-group-adapt" placeholder="<?=$Dic['Full Name of Company']?>" aria-label="<?=$Dic['Full Name of Company']?>" value="<?=$d->name?>">
                        <label for="name"><?=$Dic['Name']?>*</label>
                    </div>
                    <button class="btn btn-secondary btn-group-adapt" type="button" id="button-addon2">
                        <input type="file" class="attachment" target="logo" accept="image/*,application/pdf">
                        <input attachment type="hidden" id="logo" base64_image="" name_image="" type_image="" atual_image="<?=$d->logo?>">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                </div>


                <div class="form-floating mb-3">
                    <select name="state" class="form-control" id="state">
                        <option value="" >::<?=$Dic['Selection']?>::</option>
                        <?php
                        $q = "select * from states order by state";
                        $r = mysqli_query($con, $q);
                        while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->id?>" <?=(($s->id == $d->state)?'selected':false)?>><?=$s->state?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="state"><?=$Dic['State']?></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="addres" name="addres" placeholder="<?=$Dic['Addres']?>" value="<?=$d->addres?>">
                    <label for="addres"><?=$Dic['Addres']?>*</label>
                </div>

                <div class="input-group mb-3">
                <?php
                    if($d->contributor_tax_attachment){
                    ?>
                    <div class="imageView"><div><i class="fa-solid fa-trash"></i></div><object data="src/volume/<?=$d->id?>/<?=$d->contributor_tax_attachment?>"></object></div>
                    <?php
                    }
                    ?>
                    <div class="form-floating" style="width:calc(100% - 50px);">
                        <input type="text" name="contributor_tax" id="contributor_tax" class="form-control form-text-group-adapt" placeholder="<?=$Dic['Contributor Tax']?>" aria-label="<?=$Dic['Contributor Tax']?>" value="<?=$d->contributor_tax?>">
                        <label for="contributor_tax"><?=$Dic['Contributor Tax']?>*</label>
                    </div>
                    <button class="btn btn-secondary btn-group-adapt" type="button" id="button-addon2">
                        <input type="file" class="attachment" target="contributor_tax_attachment" accept="image/*,application/pdf">
                        <input attachment type="hidden" id="contributor_tax_attachment" base64_image="" name_image="" type_image="" atual_image="<?=$d->contributor_tax_attachment?>">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                </div>

                <div class="input-group mb-3">
                    <?php
                    if($d->commercial_record_attachment){
                    ?>
                    <div class="imageView"><div><i class="fa-solid fa-trash"></i></div><object data="src/volume/<?=$d->id?>/<?=$d->commercial_record_attachment?>"></object></div>
                    <?php
                    }
                    ?>
                    <div class="form-floating" style="width:calc(100% - 50px);">
                        <input type="text" name="commercial_record" id="commercial_record" class="form-control form-text-group-adapt" placeholder="<?=$Dic['Commercial Record']?>" aria-label="<?=$Dic['Commercial Record']?>" value="<?=$d->commercial_record?>">
                        <label for="commercial_record"><?=$Dic['Commercial Record']?>*</label>
                    </div>
                    <button class="btn btn-secondary btn-group-adapt" type="button" id="button-addon2">
                        <input type="file" class="attachment" target="commercial_record_attachment" accept="image/*,application/pdf">
                        <input attachment type="hidden" id="commercial_record_attachment" base64_image="" name_image="" type_image="" atual_image="<?=$d->commercial_record_attachment?>">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                </div>

                <div class="input-group mb-3">
                    <?php
                    if($d->bank_account_attachment){
                    ?>
                    <div class="imageView"><div><i class="fa-solid fa-trash"></i></div><object data="src/volume/<?=$d->id?>/<?=$d->bank_account_attachment?>"></object></div>
                    <?php
                    }
                    ?>
                    <div class="form-floating" style="width:calc(50% - 50px);">
                        <input type="text" name="bank_name" id="bank_name" class="form-control form-text-group-adapt" placeholder="<?=$Dic['Bank Name']?>" aria-label="<?=$Dic['Bank Name']?>" value="<?=$d->bank_name?>">
                        <label for="bank_name"><?=$Dic['Bank Name']?>*</label>
                    </div>
                    <div class="form-floating" style="width:50%;">
                        <input type="text" name="bank_account" id="bank_account" class="form-control form-text-group-adapt-between" placeholder="<?=$Dic['Bank Account']?>" aria-label="<?=$Dic['Bank Account']?>" value="<?=$d->bank_account?>">
                        <label for="bank_account"><?=$Dic['Bank Account']?>*</label>
                    </div>
                    <button class="btn btn-secondary btn-group-adapt" type="button" id="button-addon2">
                        <input type="file" class="attachment" target="bank_account_attachment" accept="image/*,application/pdf">
                        <input attachment type="hidden" id="bank_account_attachment" base64_image="" name_image="" type_image="" atual_image="<?=$d->bank_account_attachment?>">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="responsible_name" id="responsible_name" class="form-control" placeholder="<?=$Dic['Responsiblety Name']?>" value="<?=$d->responsible_name?>">
                    <label for="responsible_name"><?=$Dic['Responsiblety Name']?></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="responsible_phone" id="responsible_phone" class="form-control" placeholder="<?=$Dic['Responsiblety Phone']?>" value="<?=$d->responsible_phone?>">
                    <label for="responsible_phone"><?=$Dic['Responsiblety Phone']?></label>
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
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("#responsible_phone").mask("299999999999");

            if (window.File && window.FileList && window.FileReader) {

                $('input[type="file"]').change(function () {
                    target = $(this).attr("target");
                    if ($(this).val()) {
                        var files = $(this).prop("files");
                        for (var i = 0; i < files.length; i++) {
                            (function (file) {
                                var fileReader = new FileReader();
                                fileReader.onload = function (f) {

                                var Base64 = f.target.result;
                                var type = file.type;
                                var name = file.name;

                                $(`#${target}`).attr("base64_image", Base64);
                                $(`#${target}`).attr("type_image", type);
                                $(`#${target}`).attr("name_image", name);
                                $(`#${target}`).parent("button").parent("div").children(".imageView").remove();
                                $(`#${target}`).parent("button").parent("div").prepend(`<div class="imageView"><div><i class="fa-solid fa-trash"></i></div><object data="${Base64}"></object></div>`);

                                };
                                fileReader.readAsDataURL(file);
                            })(files[i]);
                        }
                    }
                });
            } else {
                alert('Nao suporta HTML5');
            }


            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var id = $('#id').val();
                var filds = $(this).serializeArray();

                if (id) {
                    filds.push({name: 'id', value: id})
                }

                filds.push({name: 'action', value: 'save'})

                $("input[attachment]").each(function(){
                    base64_image = $(this).attr("base64_image");
                    name_image = $(this).attr("name_image");
                    type_image = $(this).attr("type_image");
                    atual_image = $(this).attr("atual_image");
                    id = $(this).attr("id");
                    if(base64_image){
                        filds.push({name: `image-${id}-base64_image`, value:base64_image });
                        filds.push({name: `image-${id}-name_image`, value:name_image });
                        filds.push({name: `image-${id}-type_image`, value:type_image });
                        filds.push({name: `image-${id}-atual_image`, value:atual_image });
                    }
                })

                Carregando();

                $.ajax({
                    url:"src/company/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: filds,
                    success:function(dados){
                        console.log(dados);
                        // if(dados.status){
                            $.ajax({
                                url:"src/company/index.php",
                                type:"POST",
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