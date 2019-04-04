<?php
    namespace BasicORM\BORMConnections;
    /**
     * database connection interface
     */
    interface DBConnectionInterface{
        /** Query
         * Executes a query and return the results 
         */
        public static function Query($sql);
        /** ExecNonQuery
         * Executes a non query statement and returns de number of rows affected
         */
        public static function ExecNonQuery($sql);

        /** LastInsertId
         * Returns the last insert id
         */
        public static function LastInsertId();

        /** MaxId
         * Returns the max value from a field in a table
         */
        public static function MaxId($field, $table);

        /** OpenConection
         * Get a new instance of PDO using the 
         */
        public static function OpenConetion();

        /**
         * Trigger that avoid the object clonation
         */
        public function __clone();
        
    }
?>