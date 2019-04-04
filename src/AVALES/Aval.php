<?php
    namespace BasicORM\AVALES;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;

    /**
     * Clase para manejo de Alertas por diferencias en medidas
     */
    class Aval extends BORMObject implements BORMObjectInterface{
        
        /** Id del aval */
        public $idAval;
        /** Número de aval */
        public $nroAval;
        /** Número de expediente */
        public $nroExpediente;
        /** Número de registro */
        public $nroRegistro;
        /** Id de Ingeniero */
        public $idIngeniero;
        /** Id de titular */
        public $idTitular;
        /** Id de vehículo */
        public $idVehiculo;
        /** Fecha de alta del aval */
        public $fechaAlta;
        /** Pendiente (S/N) */
        public $pendiente;
        /** Es alta (S/N) si fue un aval por alta de vehículo */
        public $esAlta;
        /** Tipo de alta en caso de serlo */
        public $tipoAlta;
        /** Código de planta de ingreso */
        public $codPlanta;
        /** Fecha de finalización del aval */
        public $fechaFinalizacion;
        /** Estado actual del aval */
        public $estadoActual;
        /** Fotos en formato array, se guardan como json en la base de datos (ie. "['ABC1.jpg']") */
        public $fotos;
        /** Si es un aval por antiguedad (S/N) */
        public $porAntiguedad;
        /** Id del usuario que finalizó el aval */
        public $idUsuarioFinalizacion;

        /**
         * Constructor, sets the database mapping for the object
         */
        function __construct(){
            // Mapping con la tabla de EJEDISTANCIA
            $mappingString = '{ 
                "dbConnectionClass":"AvalesConnection",
                "dbTable" : "AVALES",
                "attributes" : {
                    "idAval" :  {"fiedlName" : "ID_AVAL", "type" : "NUMERIC", "onInsert" : "NO_INSERT", "onUpdate" : "NO_UPDATE"},
                    "nroAval" :  {"fiedlName" : "NRO_AVAL", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "nroExpediente" :  {"fiedlName" : "NRO_EXPEDIENTE", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "nroRegistro" :  {"fiedlName" : "REGISTRO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idIngeniero" :  {"fiedlName" : "ID_INGENIERO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idTitular" :  {"fiedlName" : "ID_TITULAR", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idVehiculo" :  {"fiedlName" : "ID_VEHICULO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fechaAlta" :  {"fiedlName" : "FECHA_ALTA", "type" : "DATE", "onInsert" : "NO_INSERT", "onUpdate" : "NO_UPDATE"},
                    "pendiente" :  {"fiedlName" : "PENDIENTE", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "esAlta" :  {"fiedlName" : "ID_INGENIERO", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "tipoAlta" :  {"fiedlName" : "TIPO_ALTA", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "codPlanta" :  {"fiedlName" : "COD_PLANTA", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fechaFinalizacion" :  {"fiedlName" : "FECHA_FINALIZACION", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "estadoActual" :  {"fiedlName" : "ESTADO_ACTUAL", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fotos" :  {"fiedlName" : "FOTOS", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "porAntiguedad" :  {"fiedlName" : "POR_ANTIGUEDAD", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "idUsuarioFinalizacion" :  {"fiedlName" : "USUARIO_FINALIZACION", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"}
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
            if(isset($this->idAval)){
                // Makes the update in the database
                return $this->UpdateSQL(["ID_AVAL = ".$this->idAval]) > 0;
            }else{
                // If id is not set, insert a new row in database
                $this->fecha = date_format(new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires')), 'Y-m-d H:i:s');
                if($this->InsertSQL()>0){
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
            return $this->RefreshBy(["ID_AVAL = ".$this->idAval]);
        }

        /**
         * Deletes the current object from the database
         */
        function Delete(){
            return $this->DeleteBy(["ID_AVAL = ".$this->idAval]);
        }

        /**
         * 
         */
        public function MatriculaConAval($matricula){
             return $this->SQLJoin([],[],[]);
        }

    }


?>