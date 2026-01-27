<?php
include_once "conexion.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = conectar();
    $id_usuario = $_SESSION["id_usuario"];
    $id_asignatura = $_POST['asignatura'];
    $observaciones = $_POST['observaciones'];
    $curso = date("2025-2026"); // O el curso que prefieras

    try {
        // 1. Insertar la aportación (la práctica)
        $sql = "INSERT INTO APORTACIONES (curso, nota, id_usuario, id_asignatura, id_usuarioEvalua) 
                VALUES (:curso, NULL, :id_usuario, :id_asignatura, NULL)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'curso' => $curso,
            'id_usuario' => $id_usuario,
            'id_asignatura' => $id_asignatura
        ]);
        
        $id_aportacion = $db->lastInsertId();

        // 2. Procesar las imágenes (Subir archivos y guardar en FICHERO)
        foreach (['imagen1', 'imagen2'] as $key) {
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] == 0) {
                $nombre_archivo = time() . "_" . $_FILES[$key]['name'];
                $ruta_destino = "../UPLOADS/" . $nombre_archivo;
                
                if (move_uploaded_file($_FILES[$key]['tmp_name'], $ruta_destino)) {
                    $sql_file = "INSERT INTO FICHERO (nombre, id_aportacion) VALUES (:nombre, :id_id)";
                    $db->prepare($sql_file)->execute([
                        'nombre' => $nombre_archivo,
                        'id_id' => $id_aportacion
                    ]);
                }
            }
        }
        echo json_encode(["status" => "ok", "message" => "Práctica guardada correctamente"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>