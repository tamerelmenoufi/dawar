<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


    $md5 = md5($md5.$_POST['company'].$_POST['training']);

    if($_POST['action'] == 'delete'){
        mysqli_query($con, "delete from treining_period where id = '{$_POST['id']}'");
    }

    if($_POST['status']){
        $query = "update treining_period set status = '{$_POST['opt']}' where id = '{$_POST['status']}'";
        mysqli_query($con, $query);
        exit();
    }


    if($_POST['action'] == 'save'){

        $data = $_POST;
        $attr = [];

        unset($data['action']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . addslashes($value) . "'";
        }

        $attr = implode(', ', $attr);

        $query = "insert into treining_period set {$attr}";
        $exec = mysqli_query($con, $query);
        $id = mysqli_insert_id($con);

        $return = [
            'status' => true,
            'id' => $id." - ".$query
        ];

        // echo json_encode($return);

        // exit();

    }


    $query = "SELECT * FROM `company_financial`
    where company = '{$_POST['company']}' and del != '1' and payday > 0 and
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
    $dtsx = [];
    while($d = mysqli_fetch_object($result)){
        $dt = json_decode($d->period);
        foreach($dt as $i => $v){
            $dtsx[] = $v;
        }
    }
    asort($dtsx);
    $dtsx = array_unique($dtsx);
    $dts = [];
    foreach($dtsx as $i => $v){
        $dts[] = $v;
    }
    // $dts = json_decode($d->period);

    list($year, $month, $day) = explode("-",$dts[0]);
    $number_initial = mktime(0,0,0, $month, $day, $year);
    $date_initial = date("m/d/Y", mktime(0,0,0, $month, $day, $year));
    $dti = date("Y-m-d", mktime(0,0,0, $month, $day, $year));
    if(count($dts) > 1){
        list($year, $month, $day) = explode("-",$dts[count($dts) - 1]);
        $number_final = mktime(23,59,59, $month+1, $day, $year);
        $date_final = date("m/d/Y", mktime(23,59,59, $month+1, $day, $year));
        $dtf = date("Y-m-d", mktime(23,59,59, $month+1, $day, $year));
    }else{
        list($year, $month, $day) = explode("-",$dts[0]);
        $number_final = mktime(23,59,59, $month+1, $day, $year);
        $date_final = date("m/d/Y", mktime(23,59,59, $month+1, $day, $year));
        $dtf = date("Y-m-d", mktime(23,59,59, $month+1, $day, $year));
    }

    $limit_initial = $number_initial;
    $limit_final = $number_final;

    // $q = "select min(initial_date) from treining_period where training='{$_POST['training']}' and NOW() between '".date("Y-m-d",$number_initial)."' and '".date("Y-m-d", $number_final)."'";
    // list($max_date) = mysqli_fetch_row(mysqli_query($con, $q));
    // if($max_date){
    //     list($year, $month, $day) = explode("-",$max_date);
    //     $max_date = mktime(23,59,59, $month, $day, $year);
    // }
    // $date_now = mktime(0,0,0, date("m"), date("d"), date("Y"));
    // $limit_initial = (($max_date < $date_now)?$max_date:$date_now);
    // $limit_final = mktime(23,59,59, (date("m")+6), date("d"), date("Y"));

?>


<div class="row">
    <div class="col">

        <form id="form-<?= $md5 ?>">
            <h5><?=$Dic['New Cicle']?></h5>
            <div class="input-group mb-3">
                <input type="text" readonly id="date<?=$md5?>" class="form-control DateInterval<?= $md5 ?>" placeholder="Date" aria-label="Date" value="">
                <button type="submit" class="btn btn-success"><?=$Dic['Save']?></button>
            </div>
            <input type="hidden" name="company" id="company<?=$md5?>" value="<?=$_POST['company']?>">
            <input type="hidden" name="training" id="training<?=$md5?>" value="<?=$_POST['training']?>">
            <input type="hidden" name="cost" id="cost<?=$md5?>" value="<?=$_POST['cycleCost']?>">
            <input type="hidden" name="trainings" id="trainings<?=$md5?>" value="<?=$_POST['cycleTrainings']?>">
            <input type="hidden" name="initial_date" id="initial_date<?=$md5?>" value="<?=$dti?>">
            <input type="hidden" name="final_date" id="final_date<?=$md5?>" value="<?=$dtf?>">
        </form>
        <h5><?=$Dic['Logged Cycles']?></h5>

        <?php
            $query = "select a.*, (select count(*) from treinee_admission where treining_period = a.id and del != '1') as qt from treining_period a where a.training='{$_POST['training']}' and NOW() between '".date("Y-m-d",$number_initial)."' and '".date("Y-m-d", $number_final)."' order by a.initial_date asc";
            $result = mysqli_query($con, $query);
            $i=0;
            while($d = mysqli_fetch_object($result)){

                list($year, $month, $day) = explode("-",$d->initial_date);
                $title_initial = "{$day}/{$month}/".substr($year,-2);
                $opc_initial = number_format((mktime(23,59,59, $month, $day, $year) - $limit_initial)*100/($limit_final - $limit_initial),2,'.',false);

                list($year, $month, $day) = explode("-",$d->final_date);
                $title_final = "{$day}/{$month}/".substr($year,-2);
                $opc_final = number_format((mktime(23,59,59, $month, $day, $year) - $limit_initial)*100/($limit_final - $limit_initial),2,'.',false) ;

        ?>
        <div class="row <?=(($i%2 == 0)?'bg-light':'bg-light-subtle')?>">
            <div class="col-9">
                <div class="cd-horizontal-timeline loaded d-flex align-items-center">
                    <div class="timeline">
                        <div class="events" style="width: 100%;">
                            <ol style="padding-left:5%; padding-right:5%">
                                <li>
                                    <a href="#0" class="older-event" style="left:<?=$opc_initial?>%;">
                                        <div style="position:relative;">
                                            <span class="dateInitial"><?=$title_initial?></span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" class="older-event" style="left:<?=$opc_final?>%;">
                                        <div style="position:relative;">
                                            <span class="dateFinal"><?=$title_final?></span>
                                        </div>
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 d-flex align-items-center justify-content-center">
                <div
                    style="text-align:center"
                    cycle_users<?=$md5?>="<?=$d->id?>"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasRight"
                    role="button"
                    aria-controls="offcanvasRight"
                >
                    <i class="fa-solid fa-users-viewfinder" style="cursor:pointer;"></i> <?=$d->qt?>
                </div>
            </div>
            <div class="col-1 d-flex align-items-center justify-content-center">
                <div class="form-check form-switch">
                    <input class="form-check-input status" type="checkbox" <?=(($d->status)?'checked':false)?> user="<?=$d->id?>">
                </div>
            </div>
            <div class="col-1 d-flex align-items-center justify-content-center">
                <?php
                if($d->qt){
                ?>
                <i class="fa-solid fa-trash" style="cursor: not-allowed; opacity:0.3;"></i>
                <?php
                }else{
                ?>
                <i class="fa-solid fa-trash text-danger" style="cursor:pointer" cycle_delete<?=$md5?>="<?=$d->id?>"></i>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
        $i++;
            }
        ?>
    </div>
</div>



<script>
    $(function(){

        Carregando('none');

        $('.DateInterval<?= $md5 ?>').daterangepicker({
            // "parentEl": ".MenuRight",
            "startDate": "<?=$date_initial?>",
            "endDate": "<?=$date_final?>",
            "minDate": "<?=$date_initial?>",
            "maxDate": "<?=$date_final?>",
            "opens": "<?=(($_SESSION['lng'] == 'ar')?'left':'right')?>"
        });

        $('.DateInterval<?= $md5 ?>').on('apply.daterangepicker', function(ev, picker) {
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            $(".DateInterval<?= $md5 ?>").html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))

            $("#initial_date<?=$md5?>").val(picker.startDate.format('YYYY-MM-DD'))
            $("#final_date<?=$md5?>").val(picker.endDate.format('YYYY-MM-DD'))

        });



        $('#form-<?=$md5?>').submit(function (e) {

            e.preventDefault();

            var filds = $(this).serializeArray();

            filds.push({name: 'action', value: 'save'})


            Carregando();

            $.ajax({
                url:"src/company/cycle_users.php",
                type:"POST",
                typeData:"JSON",
                mimeType: 'multipart/form-data',
                data: filds,
                success:function(dados){
                    console.log(dados);
                    // // if(dados.status){
                    //     $.ajax({
                    //         url:"src/company/cycle.php",
                    //         type:"POST",
                    //         data:{
                    //             id:"<?=$_POST['company']?>"
                    //         },
                    //         success:function(dados){
                    //             $("#pageHome").html(dados);
                    //             let myOffCanvas = document.getElementById('offcanvasRight');
                    //             let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                    //             openedCanvas.hide();
                    //         }
                    //     });
                    // // }

                    $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                },
                error:function(erro){

                    // $.alert('Ocorreu um erro!' + erro.toString());
                    //dados de teste
                }
            });

        });


        $(".status").change(function(){

            status = $(this).attr("user");
            opt = false;

            if($(this).prop("checked") == true){
            opt = '1';
            }else{
            opt = '0';
            }


            $.ajax({
                url:"src/company/cycle_users.php",
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


        $("i[cycle_delete<?=$md5?>]").click(function(){
            id = $(this).attr("cycle_delete<?=$md5?>");
            $.confirm({
                content:"<?=$Dic['Do you really want to delete the record?']?>",
                title:false,
                buttons:{
                    '<?=$Dic['Yes']?>':function(){
                        Carregando();
                        $.ajax({
                            url:"src/company/cycle_users.php",
                            type:"POST",
                            data:{
                                training:'<?=$_POST['training']?>',
                                company:'<?=$_POST['company']?>',
                                id,
                                action:'delete'
                            },
                            success:function(dados){
                                $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                            }
                        });
                    },
                    '<?=$Dic['No']?>':function(){

                    }
                }
            });
        })

        $("div[cycle_users<?=$md5?>]").click(function(){
            treining_period = $(this).attr("cycle_users<?=$md5?>");
            Carregando();
            $.ajax({
                url:"src/trainings/add.php",
                type:"POST",
                data:{
                    training:'<?=$_POST['training']?>',
                    company:'<?=$_POST['company']?>',
                    treining_period,
                },
                success:function(dados){
                    // $(`div[cycleUsers="<?=$_POST['training']?>"]`).html(dados);
                    $(".MenuRight").html(dados);
                }
            });
        })

    })
</script>