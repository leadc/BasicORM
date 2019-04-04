<?php
    namespace BasicORM\AVALES;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;

    class ReservaITE extends BORMObject implements BORMObjectInterface{
        public $id;
        public $fechaAlta; 
        public $planta;
        public $fechaReserva;
        public $idAval;
        public $matricula;
        public $estado;
        public $fechaAsignacionReserva;
        public $usuarioReserva;

        /**
         * Constructor, sets the database mapping for the object
         */
        function __construct(){
            $mappingString = '{ 
                "dbConnectionClass":"AvalesConnection",
                "dbTable" : "RESERVASAVALES",
                "attributes" : {
                    "id" :  {"fiedlName" : "ID", "type" : "NUMERIC", "onInsert" : "NO_INSERT", "onUpdate" : "NO_UPDATE"},
                    "fechaAlta" :  {"fiedlName" : "FECHAALTA", "type" : "DATE", "onInsert" : "NO_INSERT", "onUpdate" : "NO_UPDATE"},
                    "planta" :  {"fiedlName" : "PLANTA", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "fechaReserva" :  {"fiedlName" : "FECHARESERVA", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "idAval" :  {"fiedlName" : "ID_AVAL", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "matricula" :  {"fiedlName" : "MATRICULA", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "estado" :  {"fiedlName" : "ESTADO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fechaAsignacionReserva" :  {"fiedlName" : "FECHAASIGNACIONRESERVA", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "usuarioReserva" :  {"fiedlName" : "USUARIORESERVA", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"}
                }
            }';
            parent::__construct(json_decode($mappingString));
        }

        /**
         * Saves the object values in the database.
         * If id is set, updates the existing values
         */
        function Save(){
            // If id is set, update the current register in database
            if(isset($this->id)){
                // Makes the update in the database
                return $this->UpdateSQL(["ID = ".$this->id]) > 0;
            }else{
                // If id is not set, insert a new row in database
                if($this->InsertSQL()>0){
                    // Refresh get the inserted id and refresh the object values
                    $this->id = $this->Max("ID");
                    $this->Refresh();
                }
            }
        }

        /**
         * Refresh the object with the values stored in the database
         */
        function Refresh(){
            return $this->RefreshBy(["ID = '".$this->id."'"]);
        }

        /**
         * Returns all the objects stored in the database sorted by the conditions in $orderBy
         */
        function findAll($orderBy=[]){
            return $this->FindBy([],$orderBy);
        }

        function Delete(){
            return $this->DeleteBy(["ID = '".$this->id."'"]);
        }
    }


?>