<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Actualizar un poema</title>
</head>
<style>

body{
    font: oblique 120% cursive;
    background-color: #b0f2c2;
    }

button{
	background-color: violet;
}

h1{
    color: #ffffff;
    text-align:center;
}

p{
    text-align:center;
    color: green;
}

label{
    color: #ffffff;
}

img{
    display:block;
    margin:auto;
}

.btn-primary, .btn-primary:hover { border-color: pink; background-color: violet;}

</style>

<body>

<?php

require("conexion_info.php");

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<div class="container">
<a href="index.php"><img src="img/cat_index.jpg" width="400" height="200" alt="Pagina principal"></a>
<h1>Editando un poema</h1>

<?php

$id_poema = mysqli_real_escape_string($conn,$_GET["upd_id"]);

if($id_poema != ""){

    $query_by_id = "SELECT * FROM POEMA WHERE ID = $id_poema;";

    $result = mysqli_query($conn,$query_by_id);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $autor = $row["autor"];
        $titulo = $row["titulo"];
        $contenido = $row["contenido"];

        //Implemento un formulario sobre el cual aplico logica PHP
        echo "
        <div class='container'>
        <form action='' method='post'>
            <div class='row'>
                <label for='colFormLabelLg' class='col-sm-2 col-form-label col-form-label-lg'>Autor</label>
                <input type='text' class='form-control form-control-lg' id='autor' name='upd_autor' value='$autor'>
            </div>
            <div class='row'>
                <label for='colFormLabelLg' class='col-sm-2 col-form-label col-form-label-lg'>Titulo</label>
                <input type='text' class='form-control form-control-lg' id='titulo' name='upd_titulo' value='$titulo'>
            </div>
            <div class='row'>
                <label for='colFormLabelLg' class='col-sm-2 col-form-label col-form-label-lg'>Contenido</label>
                <input type='text' class='form-control form-control-lg' id='contenido' name='upd_contenido' value='$contenido'>
            </div>
            <div class='row'>
                <button type='submit' name='upd_poema' class='btn btn-primary'>Editar poema</button>
            </div>
            <a href='index.php' class='btn btn-primary'>Regresar al inicio</a>

        </form> 
        </div>
        ";

    }
    else{
        echo "<p>No existe el poema # $id_poema</p>";
    }
}

?>
<?php

    if(isset($_POST["upd_poema"])){//Evento de boton ACTUALIZAR del nuevo formulario

        $new_autor = mysqli_real_escape_string($conn,$_POST["upd_autor"]);
        $new_titulo = mysqli_real_escape_string($conn,$_POST["upd_titulo"]);
        $new_contenido = mysqli_real_escape_string($conn,$_POST["upd_contenido"]);

        $query_update = "UPDATE POEMA SET autor='$new_autor',titulo='$new_titulo',contenido='$new_contenido' WHERE ID = $id_poema;";
 
        $result = mysqli_query($conn,$query_update);

        $row_affected = mysqli_affected_rows($conn);

        if($row_affected>0){
        //echo "<p>Se edito el poema: '\t $titulo \t' de $autor</p><br>;
            //<a href='read.php?read_id=$id_poema' class='btn btn-primary'>Leer el poema editado</a>";
        header("location:read.php?read_id=$id_poema");
        }
        else{
            echo "<p>No se pudo editar el poema</p>";
        }
    }

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>
