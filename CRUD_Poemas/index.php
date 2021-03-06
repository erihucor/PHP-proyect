<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Poemas</title>
</head>
<style>

body{
    font: oblique 120% cursive;
    background-color: #b0f2c2;
    }

button{
	background-color: violet;
}

a{
    font: oblique 100% cursive;
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

/*Se va contruyendo ela estructura HTML (prioridad) y cuando se necesite agregar logica o se necesita crear
dinamicamente los elementos se usa PHP*/

?>
<div class="container">
<a href="index.php"><img src="img/cat_index.jpg" width="400" height="200" alt="Pagina principal"></a>
<h1>Busca un poema</h1>
    <form action="" method="get">
        <div class="row">
            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Autor</label>
            <div class="col-sm-11">
                <input type="text" class="form-control form-control-lg" id="autor" name="autor" placeholder="Nombre o apellido del autor">
            </div>
        </div>
        <div class="row">
            <button type="submit" name="buscar" class='btn btn-primary'>Buscar poemas por autor</button>
        </div>
    </form>

    <div class='d-grid gap-2 d-md-block'>
        <a href='create.php' class='btn btn-primary'>Ingresar un poema</a>
    </div>

</div>

<div class="container">
    <div class='accordion' id='accordionExample'>

<?php
//Paginacion

//-------------Pagination-------------
$rec_x_pag = 100;

if (isset($_GET["pag"])){
    $pag = $_GET["pag"];
}
else{
    $pag = 1;
}

$rec_inicio = ($pag - 1)*$rec_x_pag;

?>

<?php
    /*
    Seguridad ante inyeccion SQL
    Uso de mysqli_real_escape_string() para limpiar el parametro recibido
    */
    if(isset($_GET["buscar"])){//Evento de boton BUSCAR

        $busq_autor = mysqli_real_escape_string($conn,$_GET["autor"]);
        //$busq_autor = $_GET["autor"]; //VULNERABLE A INYECCION SQL

        if($busq_autor != ""){

            $query_count = "SELECT COUNT(*) as 'c' FROM POEMA WHERE AUTOR LIKE '%$busq_autor%'";

            $rec_total = mysqli_query($conn,$query_count)->fetch_object()->c;
   
            $query_by_autor = "SELECT * FROM POEMA WHERE AUTOR LIKE '%$busq_autor%' LIMIT $rec_inicio, $rec_x_pag ;";

            $result = mysqli_query($conn,$query_by_autor);

            $id = 1;
            if ($result && mysqli_num_rows($result) > 0) {//Se recorren y crean los items para cada poema
                while($row = mysqli_fetch_assoc($result)) {
                    $id_poema = $row["ID"];  //Columnas de la tabla
                    $autor = $row["autor"];
                    $titulo = $row["titulo"];
                    $contenido = $row["contenido"];

                    echo "
                    <div class='accordion-item'>
                        <h2 class='accordion-header' id='heading$id'>
                            <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$id' aria-expanded='false' aria-controls='collapse$id'>
                                Poema # $id_poema: '\t$titulo\t' de $autor
                            </button>
                        </h2>
                        <div id='collapse$id' class='accordion-collapse collapse' aria-labelledby='heading$id' data-bs-parent='#accordionExample'>
                            <div class='accordion-body'>
                                $contenido<br>
                                <a href='read.php?read_id=$id_poema'  class='btn btn-primary' >Presentar</a>
                            </div>
                        </div>
                    </div>
                    ";
                    $id+=1;
                }

                $num_pag = ceil($rec_total/$rec_x_pag);

                $HTML_pagination_start =  "
                <nav aria-label='Page navigation example'>
                    <ul class='pagination justify-content-center'>";
                        
                echo $HTML_pagination_start;

                //5 paginas antes de la actual

                $HTML_left_pag_order = "";

                $c= 0;
                $pag_left = $pag - 1;
                while($pag_left>0 && $c<5){
                    $HTML_left_pag_order = "<li class='page-item'><a class='page-link' href='index.php?autor=$busq_autor&pag=$pag_left&buscar=#'>$pag_left</a></li>" .  $HTML_left_pag_order;
                    $pag_left-=1;
                    $c+=1;
                }
                echo $HTML_left_pag_order;

                echo "<li class='page-item active'><a class='page-link' href='index.php?autor=$busq_autor&pag=$pag&buscar=#'>$pag</a></li>";

                //5 paginas despues de la actual
                $c= 0;
                $pag_right = $pag + 1;
                while($pag_right<=$num_pag && $c<5){
                    echo "<li class='page-item'><a class='page-link' href='index.php?autor=$busq_autor&pag=$pag_right&buscar=#'>$pag_right</a></li>";
                    $pag_right+=1;
                    $c+=1;
                }
                $HTML_pagination_end =  "
                    </ul>
                </nav>";
                echo $HTML_pagination_end;

            }
            else{
                echo "
                <p> No se encontraron poemas del autor: $busq_autor </p>
                ";
            }
        }
        else{
            echo "<p>Ingrese el nombre o apellido del autor para presentar sus poemas</p>";
        }

    }


    ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>
