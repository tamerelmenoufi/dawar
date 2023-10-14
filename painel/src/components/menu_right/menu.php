<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/dawar/painel/lib/includes.php");
?>
<div
    class="offcanvas offcanvas-end"
    data-bs-backdrop="static"
    tabindex="-1"
    id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel"
    style="--bs-offcanvas-width:500px;"
  >
  <div class="offcanvas-header">
    <button type="button" class="btn-close closeMenuRight" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body MenuRight"></div>
</div>

<!--
class="btn btn-primary"
data-bs-toggle="offcanvas"
href="#offcanvasRight"
role="button"
aria-controls="offcanvasRight" -->

<script>


 if( navigator.userAgent.match(/Android/i)
 || navigator.userAgent.match(/webOS/i)
 || navigator.userAgent.match(/iPhone/i)
 || navigator.userAgent.match(/iPad/i)
 || navigator.userAgent.match(/iPod/i)
 || navigator.userAgent.match(/BlackBerry/i)
 || navigator.userAgent.match(/Windows Phone/i)
 ){
    $("#offcanvasRight").css("--bs-offcanvas-width","100%")
  }
 else {
    $("#offcanvasRight").css("--bs-offcanvas-width","600px")
  }

  $(".closeMenuRight").click(function(){
    $(".MenuRight").html('');
  });

</script>