<?php
    namespace BasicORM\BORMEntities;
    use BasicORM\LOGS\Log;
    
    // Field data type Defines
    define('CAMPO_NUMERICO',"NUMERIC");
    define('CAMPO_CADENA',"STRING");
    define('CAMPO_FECHA',"DATE");
    define('CAMPO_BOOLEAN', "BOOLEAN");

    // Inser and update Defines 
    define('INSERT',1);
    define('NO_INSERT', 0);
    define('UPDATE',1);
    define('NO_UPDATE',0);

    /*  JSON Mapping Class example
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
     */

    /** BORMObject
     * Class to extend in order to create a class linked to a table database
     */
    class BORMObject{
        // Mapping BD
        protected $mapping = null;
        // date format
        protected $dateFormat = 'YYYY-MM-DD HH24:MI:SS';
        // Route to the connection class
        private static $ORMConnections="\\BasicORM\\BORMConnections\\";

        // Constructor
        function __construct($map = null){
            // If exist, set the mapping structure
            if($map != null) {
                $this->mapping = $map;
            }
        }

        /** FindBy
         * Create an array of objects applying the filters and the order
         */
        protected function FindBy($filters = [], $order = []){
            // Creates a select statement
            $sql = $this->SQLSelect($filters, $order);
            // Log::WriteLog(MAIN_LOG,$sql);
            // Executes the query
            $resultado = (self::$ORMConnections.$this->mapping->dbConnectionClass)::Query($sql);
            // Returns the results
            return $this->CreateFromQueryResult($resultado);
        }

        /** SQLSelect
         * Creates a select string that includes the filters and the order
         * $filters  (ie. ["ID = 1", "NOMBRE=Leandro"])
         * $order  (ie. ["ID ASC", "NOMBRE DESC"])
         * $join  (ie. ["inner join table_b on table_b.field = main_table.field", "inner join table_a on table_a.field = main_table.field"])
         */
        protected function SQLSelect($filters = [], $order = [], $join = []){
            // Set the table 
            $table = $this->mapping->dbTable;
            // Fields String to include in select statement
            $fields = "";
            foreach ($this->mapping->attributes as $key => $value) {
                if($fields == ""){
                    $fields = $fields . $table.".".$value->fiedlName . " AS " . $key;
                }else{
                    $fields = $fields .", ". $table.".".$value->fiedlName . " AS " . $key;
                }
            }

            // Join tables
            $joinString = "";
            for($i=0;$i<count($join);$i++){
                $joinString = $joinString . $join[$i];
            }

            // Conditions
            $where = "";
            if((count($filters) > 0)){
                $where = " WHERE ";
                for($i=0;$i<count($filters);$i++){
                    $condition = $filters[$i];
                    if($i == 0){
                        $where = "$where $condition ";
                    }else{
                        $where = "$where AND $condition ";
                    }
                }
            }
            
            // Order
            $orderString = "";
            if((count($order) > 0)){
                $orderString = "ORDER BY ";
                for($i=0;$i<count($order);$i++){
                    $orderBy = $order[$i];
                    if($i == 0){
                        $orderString = "$orderString $orderBy ";
                    }else{
                        $orderString = "$orderString, $orderBy ";
                    }
                }
            }
            // Select Statement
            $sql = "SELECT $fields FROM $table $joinString $where $orderString";
            //Log::WriteLog("LogMediciones.txt",[$sql]);
            return $sql;
        }

        /** SQLJoin
         * Executes a query making the joins in $join and return the results
         */
        protected function SQLJoin($filters = [], $order = [], $join = []){
            // Creates a select statement
            $sql = $this->SQLSelect($filters, $order, $join);
            // echo $sql;
            // Executes the query
            $resultado = (self::$ORMConnections.$this->mapping->dbConnectionClass)::Query($sql);
            // Returns the results
            return $this->CreateFromQueryResult($resultado);

        }

        /** InsertSQL
         * Generates the statement to make an insert in the table with the values in the current object
         * Returns the number of rows inserted
         */
        protected function InsertSQL(){
            // SQL Statement
            $sql = "INSERT INTO ".$this->mapping->dbTable;
            // Values in the object
            $insertFields = "";
            $fieldValues = "";
            foreach ($this->mapping->attributes as $key => $value) {
                if($value->onInsert == "INSERT"){
                    //Log::WriteLog(MAIN_LOG, ["$key -> ".$this->$key]);
                    // Fields to insert
                    if($insertFields == ""){
                        $insertFields = $insertFields . $value->fiedlName;
                    }else{
                        $insertFields = $insertFields . ", " . $value->fiedlName;
                    }

                    // Set comma ? 
                    if($fieldValues != ""){
                        $fieldValues = $fieldValues . ", ";
                    }
                    // Use quotes ?
                    $quote = "'";
                    if($value->type == "NUMERIC"){
                        $quote = "";
                    }
                    // Evaluates the current value
                    if(is_null($this->$key)){
                        $fieldValues = $fieldValues . " null";
                    }else{
                        $fieldValues = $fieldValues . "$quote" . $this->$key . "$quote";
                    }
                }
            }
            $sql = $sql." ($insertFields) VALUES ($fieldValues)";
            //Log::WriteLog(MAIN_LOG, [$sql]);
            // Executes the statement
            $result = (self::$ORMConnections.$this->mapping->dbConnectionClass)::ExecNonQuery($sql);
            // Returns the number of rows affected
            return $result;
        }

        /** Max
         * Returns the max value in $field
         */
        protected function Max($field){
            return (self::$ORMConnections.$this->mapping->dbConnectionClass)::MaxId($field, $this->mapping->dbTable);
        }

        /** UpdateSQL
         * Genera un SQL para realizar el update de registros de la tabla seleccionada según un filtro pasado por parámetro y los datos guardados en el objeto
         * Reliza el update y devuelve la cantidad de filas afectadas
         * En caso de error lanza una excepción con infromación sobre la misma
         */
        protected function UpdateSQL($filtro){
            // Inserto la tabla en la sentencia SQL
            $sql = "UPDATE ".$this->mapping->dbTable." SET ";

            // Fields String to include in select statement
            $fields = "";
            foreach ($this->mapping->attributes as $key => $value) {
                // Log::WriteLog(MAIN_LOG, ["$key -> ".$this->$key]);
                if(!is_null($this->$key) && $value->onUpdate == "UPDATE"){

                    if($fields != ""){
                        $fields = $fields .", ";
                    }

                    if($value->type == "NUMERIC"){
                        $fields = $fields . $this->mapping->dbTable.".".$value->fiedlName . " = " . $this->$key ."";
                    }else{
                        $fields = $fields . $this->mapping->dbTable.".".$value->fiedlName . " = '" . $this->$key ."'";
                    }
                }
            }
            $sql = $sql.$fields;
            // Agrego los filtros 
            for($i=0;$i<count($filtro); $i++){
                if($i == 0){
                    $sql = $sql." WHERE ".$filtro[$i];
                }else{
                    $sql = $sql." AND ".$filtro[$i];
                }
            }
            $result = (self::$ORMConnections.$this->mapping->dbConnectionClass)::ExecNonQuery($sql);
            // Devuelvo la cantidad de filas afectadas
            return $result;
        }

        /** CreateFromQueryResult
         * Create instances of the current class including the results in row
         */
        protected function CreateFromQueryResult($rows){
            $array = [];
            $clase = get_class($this);

            for($i = 0; $i < count($rows);$i++){
                $objeto = new $clase();
                foreach ($rows[$i] as $key => $value) {
                    // Buscar $key en mapping attributes
                    $objectKey = $this->findKeyName($key);
                    if($objectKey != null){
                        $objeto->$objectKey = $value;
                    }
                }
                array_push($array, $objeto);
            }
            return $array;
        }

        /**
         * Finds a key in the object 
         */
        private function findKeyName($key){
            foreach($this as $keyObject => $valueObject){
                if(\strtolower($key) == \strtolower($keyObject)){
                    return $keyObject;
                }
            }
            return null;
        }

        /**
         * Refresh the values in the objects with the values stored in database using $conditions as filter
         */
        protected function RefreshBy($conditions){
            $refreshObject = $this->FindBy($conditions);
            if(count($refreshObject) > 0){
                foreach ($this as $key => $value) {
                    $this->$key = $refreshObject[0]->$key;
                }
                return true;
            }
            return false;
        }

        /**
         * Delete objects from the database using the conditions
         */
        protected function DeleteBy($conditions){   
            $sql = "DELETE FROM ".$this->mapping->dbTable." WHERE ";
            $sql = $sql . implode(" AND ",$conditions);
            return (self::$ORMConnections.$this->mapping->dbConnectionClass)::ExecNonQuery($sql);
        }
    }
?>