<style type="text/css">
  .contenedor-alumno { margin-bottom: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; }
  .upload { opacity: 0; position: absolute; z-index: -1; }
  #upload-label, #upload-label2 { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); }
  .image-area { border: 2px dashed #368a41; padding: 10px; margin-top: 10px; border-radius: 10px; background: #fff; }
</style>

<section class="jumbotron text-center">
  <div class="container">
    <img src="IMAGENES/logo.svg" width="200" />
    <p class="lead text-muted mt-2">Evaluación continua para la Formación Profesional</p>
  </div>
</section>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="contenedor-alumno shadow-sm">
        <div class="form-group">
          <label style="font-weight: 700; color: #368a41;">Selecciona tu asignatura</label>
          <select id="asignatura" class="form-control">
            <option value="">-- Selecciona una asignatura --</option>
            <?php
            $db = conectar();
            $query = $db->query("SELECT id_asignatura, nombre FROM ASIGNATURA");
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='{$row['id_asignatura']}'>{$row['nombre']}</option>";
            }
            ?>
          </select>
        </div>

        <label class="mt-3" style="font-weight: 700; color: #368a41;">Sube las imágenes de tu práctica</label>

        <div class="input-group mt-2 mb-3 px-2 py-2 rounded-pill bg-white shadow-sm border">
          <input id="upload" type="file" onchange="readURL(this, 'imageResult', 'upload-label', 'imageResultDiv');" class="form-control border-0 upload">
          <label id="upload-label" for="upload" class="font-weight-light text-muted">Foto antes de iniciar</label>
          <div class="input-group-append">
            <label for="upload" class="btn btn-light m-0 rounded-pill px-4"><i class="fas fa-cloud mr-2"></i>SUBIR</label>
          </div>
        </div>
        <div class="image-area text-center" id="imageResultDiv" style="display: none;">
          <img id="imageResult" src="#" class="img-fluid rounded" width="150px">
        </div>

        <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm border">
          <input id="upload2" type="file" onchange="readURL(this, 'imageResult2', 'upload-label2', 'imageResultDiv2');" class="form-control border-0 upload">
          <label id="upload-label2" for="upload2" class="font-weight-light text-muted">Foto al acabar</label>
          <div class="input-group-append">
            <label for="upload2" class="btn btn-light m-0 rounded-pill px-4"><i class="fas fa-cloud mr-2"></i>SUBIR</label>
          </div>
        </div>
        <div class="image-area text-center" id="imageResultDiv2" style="display: none;">
          <img id="imageResult2" src="#" class="img-fluid rounded" width="150px">
        </div>

        <label class="mt-3" style="font-weight: 700; color: #368a41;">Explica cómo has hecho tu práctica</label>
        <textarea id="observaciones" class="form-control" rows="3" placeholder="Describe brevemente el proceso..."></textarea>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success btn-lg px-5" onclick="generarPDF()">GENERAR PDF</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function readURL(input, imgId, labelId, divId) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(imgId).src = e.target.result;
        document.getElementById(divId).style.display = "block";
        document.getElementById(labelId).textContent = 'Imagen: ' + input.files[0].name;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  async function generarPDF() {
    const btn = event.target;
    const originalText = btn.innerText;
    
    const urlLocal = 'FUNCIONES/procesar_practica.php';
    const urlPDF = 'https://alumnos.preinteriorismo.es/api/downloadpdf.php';

    const f1 = document.getElementById('upload').files[0];
    const f2 = document.getElementById('upload2').files[0];
    const asig = document.getElementById('asignatura').value;
    const obs = document.getElementById('observaciones').value;

    if (!f1 || !f2 || asig === "") {
      alert("Faltan datos o imágenes.");
      return;
    }

    btn.innerText = "PROCESANDO...";
    btn.disabled = true;

    const formData = new FormData();
    formData.append('asignatura', asig);
    formData.append('imagen1', f1);
    formData.append('imagen2', f2);
    formData.append('observaciones', obs);
    formData.append('alumno', '<?php echo $_SESSION["name"] ?? "Alumno"; ?>');

    try {
      // 1. Guardar en tu servidor
      await fetch(urlLocal, { method: 'POST', body: formData });

      // 2. Obtener PDF
      const resp = await fetch(urlPDF, { method: 'POST', body: formData });
      
      if (!resp.ok) throw new Error("Error en servidor de PDF");

      const blob = await resp.blob();
      const url = URL.createObjectURL(blob);
      
      // Abrir PDF
      const win = window.open(url, '_blank');
      if (!win) alert("Permite las ventanas emergentes para ver el PDF.");

    } catch (err) {
      console.error(err);
      alert("Error al conectar con el generador de PDF. Verifica que el servidor externo esté activo.");
    } finally {
      btn.innerText = originalText;
      btn.disabled = false;
    }
  }
</script>