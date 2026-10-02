<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$sesioniniciada = isset($_SESSION["usuario"]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bookify - Biblioteca digital</title>
  <link rel="stylesheet" href="clases.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

  <header>
    <h1>
    <img src="imágenes/LOGO.png" alt="">
    <span>Bookify</span>
</h1>
    <nav>
      <?php
      $activoindice="";
      $activocatalogo="";
      if($localizacion=="indice"){
        $activoindice= "class='active'";
      }else if($localizacion=="catalogo"){
        $activocatalogo= "class='active'";
      }
      ?>
      <a href="index.php" <?=$activoindice?>>Inicio</a>
      <a href="catalogo.php" <?=$activocatalogo?>>Catálogo</a>
      <details class="cuenta-menu">
        <summary>👤 <?= $sesioniniciada ? htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8") : "Mi cuenta" ?> ▾</summary>
        <div class="cuenta-desplegable">
          <div class="cuenta-titulo">👤 <?= $sesioniniciada ? htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8") : "Mi cuenta" ?></div>
          <div class="cuenta-opciones">
            <?php if ($sesioniniciada): ?>
            <?php if (($_SESSION["rol"] ?? "") === "admin"): ?>
              <a href="admin.php">Gestión de reservas</a>
            <?php else: ?>
              <a href="panelusuario.php">Mi Área Personal</a>
            <?php endif; ?>
            <a href="logout.php">Cerrar sesión</a>
          <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>
          <?php endif; ?>
          </div>
        </div>
      </details>
    </nav>
  </header>