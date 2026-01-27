<?php
session_start();
include __DIR__ . '/include/functions.php'; // Asegúrate de que aquí está tu función conectarBD()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dni       = trim($_POST['dni'] ?? '');
    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $rol  = trim($_POST['rol'] ?? '');


    if ($dni === '' || $nombre === '' || $email === '' || $password === ''| $rol === '' |)
    {
        echo "<p>Todos los campos obligatorios deben completarse.</p>";
        exit;
    }

    // Conectar a la BD
    $conn = conectarBD();
    if (!$conn)
    {
        echo "<p>Error al conectar a la base de datos.</p>";
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO USUARIO (dni, nombre, apellido1, apellido2, email, nick, password, activo, externo)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0)
    ");
    $stmt->bind_param("sssssss", $dni, $nombre, $email, $rol, $passwordHash);

    if ($stmt->execute()) {
        echo "<p>Usuario registrado correctamente.</p>";
    } else {
        echo "<p>Error al registrar el usuario: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Cerrar conexiones
    $stmt->close();
    $conn->close();
}
?>

<!-- Formulario simple -->
<form method="POST" action="">
    <label>DNI: <input type="text" name="dni" required></label><br>
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>rol: <input type="text" name="rol" required></label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <button type="submit">Registrar usuario</button>
</form>