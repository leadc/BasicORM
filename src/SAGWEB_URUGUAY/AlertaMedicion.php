<?php
    namespace BasicORM\SAGWEB_URUGUAY;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;

    /**
     * Clase para manejo de Alertas por diferencias en medidas
     */
    class AlertaMedicion extends BORMObject implements BORMObjectInterface{
        
        /** Id de usuario del cajero */
        public $idUsuario;
        /** Id de usuario del supervisor */
        public $idSupervisor;
        /** Código del vehículo */
        public $codvehic;
        /** Código de inspección */
        public $codinspe;
        /** Motivo que describen para continuar con la inspección */
        public $motivo;
        /** Fecha de alerta */
        public $fecha;
        /** Alertas disparadas */
        public $alerta;
        /** Id de revisor */
        public $idRevisor;


        /**
         * Constructor, sets the database mapping for the object
         */
        function __construct(){
            // Mapping con la tabla de EJEDISTANCIA
            $mappingString = '{ 
                "dbConnectionClass":"PlantaConnection",
                "dbTable" : "ALERTA_MEDICIONES",
                "attributes" : {
                    "idUsuario" :  {"fiedlName" : "IDUSUARIOLOGEO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "idSupervisor" :  {"fiedlName" : "IDUSUARIOSUPERVISOR", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "codvehic" :  {"fiedlName" : "CODVEHIC", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "codinspe" :  {"fiedlName" : "CODINSPE", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "motivo" :  {"fiedlName" : "MOTIVO", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fecha" :  {"fiedlName" : "FECHA", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "alerta" :  {"fiedlName" : "ALERTA", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idRevisor" :  {"fiedlName" : "IDREVISOR", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"}
                }
            }';

            # Initial Values...
            
            parent::__construct(json_decode($mappingString));
        }

        /**
         * Saves the object values in the database.
         * If id is set, updates the existing values
         */
        function Save(){
            // If id is set, update the current register in database
            if(isset($this->fecha)){
                // Makes the update in the database
                return $this->UpdateSQL(["CODINSPE = ".$this->codinspe, "CODVEHIC = ".$this->codvehic, "FECHA = '".$this->fecha."'"]) > 0;
            }else{
                // If id is not set, insert a new row in database
                $this->fecha = date_format(new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires')), 'Y-m-d H:i:s');
                $filesAffected = $this->InsertSQL();
                if($filesAffected > 0){
                    // Refresh get the inserted id and refresh the object values
                    return $this->Refresh();
                }
            }
            return false;
        }

        /**
         * Refresh the object with the values stored in the database
         */
        function Refresh(){
            return $this->RefreshBy(["CODVEHIC = ".$this->codvehic, "TO_CHAR(FECHA,'YYYY-MM-DD') = TO_CHAR('".$this->fecha."','YYYY-MM-DD')"]);
        }

        /**
         * Deletes the current object from the database
         */
        function Delete(){
            return $this->DeleteBy(["CODVEHIC = ".$this->codvehic, "TO_CHAR(FECHA,'YYYY-MM-DD') = TO_CHAR('".$this->fecha."','YYYY-MM-DD')"]);
        }

        /**
         * Append a string to the end of the alert
         */
        function AppendAlerta($alerta){
            if(strlen($alerta) > 0){
                $alertas = explode(PHP_EOL, $this->alerta);
                array_push($alertas, $alerta);
                $this->alerta = implode(PHP_EOL, $alertas);
                return true;
            }
            return false;
        }

        /**
         * Sets the revisor id, returns true on success
         */
        function SetRevisor($id){
            if(is_numeric($id) && $id > 0){
                $this->idRevisor = $id;
                return true;
            }
            return false;
        }


    }


?>