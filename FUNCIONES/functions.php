<?php

define("APP_URL", "https://practicas.opiedevelopment.website");
define("API_URL", APP_URL . "/api");

function verificar_usuario($email, $pass) {
    $db = conectar(); // Usamos la conexión que creamos antes
    
    // Buscamos al usuario por su email
    $sql = "SELECT * FROM USUARIO WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();

    // Verificamos si existe y si la password coincide
    // (Uso comparación directa porque en tu DB las tienes en texto plano por ahora)
    if ($usuario && $usuario['password'] === $pass) {
        return $usuario;
    }
    
    return false;
}


?>