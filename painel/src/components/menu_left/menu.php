<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
?>

<style>
.menu-cinza{
  padding:8px;
  font-size:15px;
  border-bottom:1px solid #d7d7d7;
  cursor:pointer;
}

.texto-cinza{
  color:#5e5e5e;
}

</style>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="img/logocred2.png" style="height:43px;" alt="">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <h4 style="color:#057a34"><?=$Dic['TCV - Control Panel']?></h4>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a href="<?=(($_SESSION['lng'] == 'ar')?"?lng=en":"?lng=ar")?>" class="text-decoration-none texto-cinza">
          <i class="fa-solid fa-language"></i> <?=(($_SESSION['lng'] == 'ar')?'English':'عربي')?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line"></i> <?=$Dic['Dashboard']?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/categories/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-sitemap"></i> <?=$Dic['Categories']?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/company/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-building"></i> <?=$Dic['Company']?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/faculties/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-school"></i> <?=$Dic['Faculties']?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/states/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-mountain-city"></i> <?=$Dic['States']?>
        </a>
      </div>
    </div>

    <div class="row mb-1 menu-cinza">
      <div class="col">
        <a url="src/users/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
         <i class="fa-solid fa-users"></i> <?=$Dic['System Users']?>
        </a>
      </div>
    </div>




    <!-- <div class="row  mb-1 menu-cinza">
      <div class="col">
        <a url="src/configuracoes/index.php" class="text-decoration-none texto-cinza" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-gears"></i> <?=$Dic['Settings']?>
        </a>
      </div>
    </div> -->


  </div>
</div>

<script>
  $(function(){
    $("a[url]").click(function(){
      Carregando();
      url = $(this).attr("url");
      $.ajax({
        url,
        success:function(dados){
          $("#pageHome").html(dados);
        }
      });
    });
  })
</script>