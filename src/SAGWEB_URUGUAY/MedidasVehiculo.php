<?php
    namespace BasicORM\SAGWEB_URUGUAY;
    use BasicORM\BORMEntities\BORMObject;
    use BasicORM\BORMEntities\BORMObjectInterface;

    /**
     * Objeto para manejar las medias de vehículos en la tabla EJEDISTANCIA
     */
    class MedidasEjedistancia extends BORMObject implements BORMObjectInterface{

        /** Codigo de inspección */
        public $codinspe;
        /** Código de vehículo */
        public $codvehic;
        /** Cantidad de ejes del vehículo */
        public $numejes;
        /** Distancia a cola */
        public $cola;
        /** Distancia a punto de enganche */
        public $dpe;
        /** Número de chásis */
        public $numchasis;
        /** Distancia entre ejes 1 - 2 */
        public $disejes_1_2;
        /** Distancia entre ejes 2 - 3 */
        public $disejes_2_3;
        /** Distancia entre ejes 3 - 4 */
        public $disejes_3_4;
        /** Distancia entre ejes 4 - 5 */
        public $disejes_4_5;
        /** Distancia entre ejes 5 - 6 */
        public $disejes_5_6;
        /** Distancia entre ejes 6 - 7 */
        public $disejes_6_7;
        /** Distancia entre ejes 7 - 8 */
        public $disejes_7_8;
        /** Distancia entre ejes 8 - 9 */
        public $disejes_8_9;
        /** Alto del vehículo */
        public $alto;
        /** Ancho del vehículo */
        public $ancho;
        /** Largo del vehículo */
        public $largo;
        /** Código de inspector que realizó la revisión */
        public $numrevis;
        /** Fecha de alta del registro */
        public $fecha_alta;

        /**
         * Constructor, sets the database mapping for the object
         */
        function __construct(){
            // Mapping con la tabla de EJEDISTANCIA
            $mappingString = '{ 
                "dbConnectionClass":"PlantaConnection",
                "dbTable" : "EJEDISTANCIA",
                "attributes" : {
                    "codinspe" :  {"fiedlName" : "CODINSPE", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "codvehic" :  {"fiedlName" : "CODVEHIC", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"},
                    "numejes" :  {"fiedlName" : "NUMEJES", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "cola" :  {"fiedlName" : "COLA", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "dpe" :  {"fiedlName" : "DPE", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "numchasis" :  {"fiedlName" : "NUMCHASIS", "type" : "STRING", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_1_2" :  {"fiedlName" : "DISEJES12", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_2_3" :  {"fiedlName" : "DISEJES23", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_3_4" :  {"fiedlName" : "DISEJES34", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_4_5" :  {"fiedlName" : "DISEJES45", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_5_6" :  {"fiedlName" : "DISEJES56", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_6_7" :  {"fiedlName" : "DISEJES67", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_7_8" :  {"fiedlName" : "DISEJES78", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "disejes_8_9" :  {"fiedlName" : "DISEJES89", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "alto" :  {"fiedlName" : "alto", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "ancho" :  {"fiedlName" : "ancho", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "largo" :  {"fiedlName" : "largo", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "numrevis" :  {"fiedlName" : "NUMREVIS", "type" : "NUMERIC", "onInsert" : "INSERT", "onUpdate" : "UPDATE"},
                    "fecha_alta" :  {"fiedlName" : "FECHALTA", "type" : "DATE", "onInsert" : "INSERT", "onUpdate" : "NO_UPDATE"}
                }
            }';

            // Valores iniciales para evitar insertar nulos en campos de medidas
            $this->cola = 0;
            $this->numejes = 0;
            $this->dpe = 0;
            $this->numchasis = 0;
            $this->disejes_1_2 = 0;
            $this->disejes_2_3 = 0;
            $this->disejes_3_4 = 0;
            $this->disejes_4_5 = 0;
            $this->disejes_5_6 = 0;
            $this->disejes_6_7 = 0;
            $this->disejes_7_8 = 0;
            $this->disejes_8_9 = 0;
            $this->alto = 0;
            $this->ancho = 0;
            $this->largo = 0;

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
            if(isset($this->codinspe)){
                // Makes the update in the database
                return $this->UpdateSQL(["CODINSPE = ".$this->codinspe, "CODVEHIC = ".$this->codvehic]) > 0;
            }else{
                // If id is not set, insert a new row in database
                $this->codinspe = '-102';
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