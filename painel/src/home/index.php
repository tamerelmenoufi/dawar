<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
?>
<div id="pageHomeTop"></div>
<div id="pageHomeLeft"></div>
<div id="pageHomeRight"></div>
<div id="pageHome"></div>
<script>

    function Open(u, l){
        Carregando();
        $.ajax({
            url:u,
            success:function(data){
                $(`#${l}`).html(data);
                Carregando('none');
            }
        });
    }

    $(function(){
        pags = [
            ['src/components/menu_top/menu.php','pageHomeTop'],
            ['src/components/menu_left/menu.php','pageHomeLeft'],
            ['src/components/menu_right/menu.php','pageHomeRight'],
            ['src/dashboard/index.php','pageHome'],
            ];

        for(i=0;i<pags.length;i++){
            url = pags[i][0];
            local = pags[i][1];
            Open(url, local);
        }

    })
</script>