<?php

    include_once __DIR__.'/BasicORM.php';

    use BasicORM\SAGWEB_URUGUAY\AlertaMedicion;
    
    $alerta  = new AlertaMedicion();
    $alerta->Save();
    echo json_encode($alerta);
    //echo json_encode($medidas);
?>