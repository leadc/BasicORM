<?php

    namespace BasicORM\BORMConnections;
   
    use \PDO as PDO;

    /**
     * Conexión a la base de datos de Avales
     */
    class ConnectionClass implements DBConnectionInterface{

        /**
         * DSN Connection
         */
        protected static $dsn = '';
        /**
         * database User
         */
        protected static $user = '';
        /**
         * database user password
         */
        protected static $pass = '';
        /**
         * Options to apply in connection
         */
        protected static $options = [];
        /**
         * Statements to execute when connection is opened
         */
        protected static $initStatements = [];
        /**
         * PDO object connection
         */
        private static $PDO = null;
        
        /** Query
         * Executes a query and return the results 
         */
        public static function Query($sql)
        {
            if (self::$PDO == null) {
                self::OpenConetion();
            }
            $stmt = self::$PDO->prepare($sql);
            $stmt->execute();
            return $stmt->fetchall();
        }
        
        /** ExecNonQuery
         * Executes a non query statement and returns de number of rows affected
         */
        public static function ExecNonQuery($sql)
        {
            if (self::$PDO == null) {
                self::OpenConetion();
            }
            return self::$PDO->exec($sql);
        }

        /** LastInsertId
         * Returns the last insert id
         */
        public static function LastInsertId()
        {
            return self::$PDO->lastInsertId();
        }

        /** MaxId
         * Returns the max value from a field in a table
         */
        public static function MaxId($field, $table)
        {
            $sql_lastId = "SELECT MAX($field) AS ID FROM $table";
            
            $id = self::Query($sql_lastId);
            if(count($id)>0){
                return $id[0]['ID'];
            }
            return false;
        }

        /** OpenConection
         * Get a new instance of PDO using the 
         */
        public static function OpenConetion()
        {
            self::$PDO = new PDO(self::$dsn, self::$user, self::$pass,self::$options);
            for($i = 0; $i<count(self::$initStatements); $i++){
                self::ExecNonQuery(self::$initStatements[$i]);
            }
        }

        /**
         * Trigger that avoid the object clonation
         */
        public function __clone()
        {
            trigger_error("It object can't be cloned", E_USER_ERROR);
        }
    }
?>