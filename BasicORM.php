<?php
    namespace BasicORM;
    
    /**
     *  Include all the conection classes here
     */
    // Connection Interface
    require_once __DIR__.'/src/BORMConnections/DBConnectionInterface.php';
    // Avales Connection class
    // require_once __DIR__.'/BORMConnections/AvalesConnection.php';
    // Planta Connection
    require_once __DIR__.'/src/BORMConnections/PlantaConnection.php';

    // Class entities
    require_once __DIR__.'/src/BORMEntities/BORMObject.php';
    require_once __DIR__.'/src/BORMEntities/BORMObjectInterface.php';
    //require_once __DIR__.'/BORMEntities/ReservaITE.php';

    // SAGWEB_URUGUAY Entities
    //require_once __DIR__.'/SAGWEB_URUGUAY/MedidasVehiculo.php';
    require_once __DIR__.'/src/SAGWEB_URUGUAY/AlertaMedicion.php';
    //require_once __DIR__.'/SAGWEB_URUGUAY/Revisor.php';


    


