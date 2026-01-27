<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
include_once "FUNCIONES/conexion.php";
include_once "FUNCIONES/functions.php";
include_once "assets/jwt/JWT.php";
include_once "assets/auth.php";
use Firebase\JWT\JWT;

date_default_timezone_set('Europe/Madrid');
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <?php include("INCLUDES/head.php"); ?>
    </head>

    <body>

        <header>
            <?php include("INCLUDES/menu.php"); ?>
        </header>

        <main style="margin-top: 56px;">
            <?php include("INCLUDES/pages.php"); ?>
        </main>

    </body>

</html>

<?php
ob_end_flush();
?>