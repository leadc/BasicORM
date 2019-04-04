<?php
    namespace BasicORM\SAGWEB_URUGUAY;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;

    class Revisor extends BORMObject implements BORMObjectInterface{
        

        public $idUsuario;
        public $idSupervisor;
        public $codvehic;
        public $codinspe;
        public $motivo;
        public $fecha;
        public $alerta;

        /**
         * Constructor, sets the database mapping for the object
         */
        function __construct(){
            // Mapping con la tabla de EJEDISTANCIA
            $mappingString = '{ 
                "dbConnectionClass":"PlantaConnection",
                "dbTable" : "TREVISOR",
                "attributes" : {
                    "numrevis" :  {"fiedlName" : "NUMREVIS", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "fechaAlta" :  {"fiedlName" : "FECHALTA", "type" : "DATE", "onInsert" : "NO_INSERT", "onUpdate" : "NO_UPDATE"},
                    "clave" :  {"fiedlName" : "PALCLAVE", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "esSupervisor" :  {"fiedlName" : "ESSUPERV", "type" : "BOOLEAN", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "nombreRevisor" :  {"fiedlName" : "NOMREVIS", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "activo" :  {"fiedlName" : "ACTIVO", "type" : "BOOLEAN", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "legajo" :  {"fiedlName" : "LEGAJO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"}
                }
            }';

            // Valores iniciales para evitar insertar nulos en campos de medidas
            parent::__construct(json_decode($mappingString));
        }

        /**
         * Returns all the objects stored in the database sorted by the conditions in $orderBy
         */
        function FindAll($orderBy=[]){
            return $this->FindBy([],$orderBy);
        }

        /**
         * Saves the object values in the database.
         * If id is set, updates the existing values
         */
        function Save(){
            // If id is set, update the current register in database
            if(isset($this->fecha)){
                // Makes the update in the database
                return $this->UpdateSQL(["CODINSPE = ".$this->codinspe, "CODVEHIC = ".$this->codvehic]) > 0;
            }else{
                // If id is not set, insert a new row in database
                if($this->InsertSQL()>0){
                    // Refresh get the inserted id and refresh the object values
                    $this->Refresh();
                }
            }
        }

        /**
         * Refresh the object with the values stored in the database
         */
        function Refresh(){
            return $this->RefreshBy(["CODINSPE = ".$this->codinspe, "CODVEHIC = ".$this->codvehic]);
        }

        /**
         * Deletes the current object from the database
         */
        function Delete(){
            return $this->DeleteBy(["CODINSPE = ".$this->codinspe, "CODVEHIC = ".$this->codvehic]);
        }
    }


?>