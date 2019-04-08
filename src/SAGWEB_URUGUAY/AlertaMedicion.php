<?php
    namespace BasicORM\SAGWEB_URUGUAY;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;
    use BasicORM\LOGS\Log;
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
                    "codinspe" :  {"fiedlName" : "CODINSPE", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "motivo" :  {"fiedlName" : "MOTIVO", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fecha" :  {"fiedlName" : "FECHA", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "alerta" :  {"fiedlName" : "ALERTA", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idRevisor" :  {"fiedlName" : "IDREVISOR", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"}
                }
            }';

            # Initial Values...
            $this->fecha = '';
            
            parent::__construct(json_decode($mappingString));
        }

        /**
         * Saves the object values in the database.
         * If id is set, updates the existing values
         */
        function Save(){
            // Makes the update in the database
            if($this->UpdateSQL(["CODVEHIC = ".$this->codvehic, "FECHA = '".$this->fecha."'"]) == 0){
                // If id is not set, insert a new row in database
                $this->fecha = date_format(new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires')), 'Y-m-d H:i:s');
                if($this->InsertSQL() > 0){
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
            if($this->fecha != ''){
                return $this->RefreshBy(["CODVEHIC = ".$this->codvehic, "SUBSTR(FECHA,0,10) = TO_DATE('".mb_strcut($this->fecha,0,10)."','YYYY-MM-DD')"]);
            }else{
                return $this->RefreshBy(["CODVEHIC = ".$this->codvehic, "SUBSTR(FECHA,0,10) = SUBSTR(SYSDATE,0,10)"]);
            }
        }

        /**
         * Deletes the current object from the database
         */
        function Delete(){
            return $this->DeleteBy(["CODVEHIC = ".$this->codvehic, "SUBSTR(FECHA,0,10) = TO_DATE('".mb_strcut($this->fecha,0,10)."','YYYY-MM-DD')"]);
        }

        /**
         * Append a string to the end of the alert
         */
        function AppendAlerta($alerta){
            if(strlen($alerta) > 0){
                if(strlen($this->alerta) > 0){
                    $this->alerta = $this->alerta . PHP_EOL .$alerta;
                }else{
                    $this->alerta = $alerta;
                }
                return true;
            }
            return false;
        }

        /**
         * Busca una alerta del día apra un código de vehículo y la carga la instancia actual del objeto
         * Devuelve true en caso de encontrar una alerta o false en caso de no
         */
        function FindLastByCodvehic($codvehic){
            $alerta = $this->FindBy(["CODVEHIC = $codvehic","SUBSTR(FECHA,0,10) = SUBSTR(SYSDATE,0,10)"]);
            if(count($alerta)>0){
                foreach($this as $key => $value){
                    $this->$key = $alerta[0]->$key;
                }
                return true;
            }
            return false;
        }

        /**
         * Sets the alert proppery to ''
         */
        function VaciarAlertas(){
            $this->alerta = '';
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

        /**
         * Marca una inspección para que no sea continuada
         */
        function NoContinuarInspeccion(){
            // Si la inspección aún no fué vista por un supervisor y el estado es -102
            if(is_null($this->idSupervisor) && $this->codinspe == -102){
                // Seteo los valores para que la inspección no continúe
                $this->codinspe = -1;
                $this->motivo = 'NO CONTINUA CON LA INSPECCION';
                $this->Save();
                return true;
            }
            return false;
        }

        /**
         * Marca una inspección para que pueda continuarse a pesar de las alertas (Necesitará clave de supervisor en caja)
         */
        function ContinuarInspeccion(){
            if($this->codinspe == -1 && $this->motivo == 'NO CONTINUA CON LA INSPECCION'){
                $this->codinspe = -102;
                $this->motivo = '';
                $this->Save();
                return true;
            }
            return false;
        }

    }


?>