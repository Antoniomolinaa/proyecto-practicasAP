<nav>
    
  <?php 
  if(!isset($_SESSION['token'])) 
  {
  ?>

    <nav id="menu" class="navbar navbar-expand-lg navbar-light bg-primary">
        <a class="navbar-brand">
            <img src="IMAGENES/logo_basico.svg" width="30" style="filter: brightness(0) invert(1);"/>
        </a>

        <form class="form-inline">
        </form>
    </nav>
            
  <?php
  }
  else
  {  
    
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-primary fixed-top">
      <a class="navbar-brand" href="index?page=landing">
            <img src="IMAGENES/logo_basico.svg" width="30" style="filter: brightness(0) invert(1);"/>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse " id="navbarNav">
        <ul class="navbar-nav">

          <form class="form-inline ">
            <a class="text-white mr-3"><i class="fas fa-user"></i> <?php echo $_SESSION["name"];?></a>
            <a href="index?page=logout" class="btn btn-light my-2 my-sm-0" role="button"><i class="fas fa-power-off"></i> Cerrar sesión</a>
          </form>
          
        </ul>
      </div>

      
    </nav>


    <?php
  }

?>