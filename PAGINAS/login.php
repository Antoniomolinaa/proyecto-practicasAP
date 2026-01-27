  <center>
  <div id="logindiv">
    <br />
    <br />
    <br />

    <div class="card text-center bg-light" style="max-width: 30rem; box-shadow: 0px 5px 30px #545454;">
      <div class="card-header">
        <b>INICIO DE SESIÓN</b>
      </div>
      <div class="card-body">

            <br />
            <img src="IMAGENES/logo.svg" width="300"/>
            <br />
            <br />
            <br />
        
            <form name="form" method="POST" enctype="multipart/form-data">

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                  </div>
                  <input type="text" name="nick" placeholder="Usuario" required class="form-control">
                </div>

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="password" name="password" placeholder="Contraseña" required class="form-control"/>
                </div>
                                            
                <br />
                <input class="btn btn-primary" name="acceptbutton" type="submit" value="ACCEDER">

            </form>

      </div>
      <div class="card-footer">
        Academia Main() © <?php echo date("Y");?>
      </div>
    </div>
  </div>
  </center>


<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modallabel">Error de acceso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        El usuario o contraseña proporcionados no son correctos. Inténtelo de nuevo.
        </div>
        <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-primary">Aceptar</button>
        </div>
    </div>
    </div>
</div>

                        

<?php

if(isset($_POST["acceptbutton"]))
{
    $user_input = $_POST["nick"]; // El 'nick' del formulario lo usaremos como email
    $pass_input = $_POST["password"];

    // Llamamos a la función que busca en la base de datos
    $datos_usuario = verificar_usuario($user_input, $pass_input);

    if($datos_usuario)
    {
        // Si los datos son correctos, guardamos la info real de la DB en la sesión
        $_SESSION["id_usuario"] = $datos_usuario["id_usuario"];
        $_SESSION["name"] = $datos_usuario["nombre"];
        $_SESSION["id_rol"] = $datos_usuario["id_rol"]; // Muy importante para saber qué puede ver

        // Aquí podrías generar tu JWT real
        $_SESSION["token"] = "token_real_generado"; 

        header("Location: index.php");
        exit;
    }
    else
    {
        // Si falla, mostramos el modal de error que ya tenías
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#modal").modal('show');
            });
        </script>
        <?php
    }
}
?>