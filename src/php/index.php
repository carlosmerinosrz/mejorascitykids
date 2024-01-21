<?php

    require_once __DIR__ .'/config/configdb.php';

    if(!isset($_GET["c"])) $_GET["c"] = constant("CONTROLADOR_DEFECTO");
    if(!isset($_GET["m"])) $_GET["m"] = constant("METODO_DEFECTO");

    require_once __DIR__ .'/controllers/'.$_GET['c'].'.php';
    $controladorNombre = $_GET['c'];
    $controlador = new $controladorNombre();

    $metodo = $_GET['m'];

    // Obtener los datos del método del controlador
    $datos = $controlador->$metodo();

    // Obtener el mensaje de error del controlador si existe
    $mensajeError = isset($controlador->mensaje) ? $controlador->mensaje : '';

    //Tenemos dos tipos de vistas con .html o .php, pues para que valgan para las dos:
    $vistasPhp = __DIR__ . '/views/' . $controlador->view . '.php';
    $vistasHtlm = __DIR__ . '/views/' . $controlador->view . '.html';
    
   // Cargar Vistas
    require_once __DIR__ .'/views/template/header.php';

    //Seleccion del tipo de vistas
    if(file_exists($vistasPhp))
        require_once($vistasPhp);
    elseif(file_exists($vistasHtml))
        require_once($vistasHtml);
    else
        require_once __DIR__ . '/views/vError404.html';

    require_once __DIR__ .'/views/template/footer.php';
?>
