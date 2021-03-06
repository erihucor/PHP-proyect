<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Informacion</title>

    <?php
      if(isset($_COOKIE["mode"])){//Lee la cookie de mode
        if($_COOKIE["mode"] == "dark"){
          echo "<link rel='stylesheet' href='css/dark.css'>";
        }
        else{
          echo "<link rel='stylesheet' href='css/light.css'>";
        }
      }//Si no existe la cookie la creo por primera vez
      else{
        header("Location:cookie_mode.php?pag=info");
      }
    ?>
</head>
<style>
h1,h2,p {
    text-align: center;
}
h3{
    font-size:x-large ;
}
</style>
<body>
<?php
//Verificar si existe una session iniciada, y si en esa session se almaceno un usuario
session_start();
if(!isset($_SESSION["id_usuario"])){//Si no esta definida la variable "usuario" en $_SESSION, se redireccina
    header("location:login.php");
}
else{
    require_once("script/conexion.php");//Objeto conn

    $id_usuario = $_SESSION["id_usuario"];

    $query = "SELECT * FROM info_usuarios WHERE id_usuario = ? ";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($id_usuario));

    if($stmt->rowCount()>0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        header("location:login.php");
    }

}
?>
<div class="container dark_text">
    <div class="row justify-content-center">
        <h1>Pagina de informacion</h1>
        <h2>Datos personales</h2>

        <h3>Nombre: <?php echo $row["nombre"]; ?></h3>
        <h3>Apellido: <?php echo $row["apellido"]; ?></h3>
        <h3>Descripcion:</h3>
        <p> <?php echo $row["descripcion"]; ?> </p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <a class="btn btn-primary" href="index.php">Inicio</a>
        </div>
        <div class="col-md-6 text-center">
            <a class="btn btn-primary" href="script/close_sesion.php">Cerrar sesion</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <a class="btn btn-primary" href="cookie_mode.php?pag=info">Cambiar tema</a>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</body>
</html>