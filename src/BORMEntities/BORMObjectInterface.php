<?php
    namespace BasicORM\BORMEntities;

    interface BORMObjectInterface{

        /**
         * Save function to store or update the object in the database
         */
        function Save();

        /**
         * Deletes the current object from the database
         */
        function Delete();

        /**
         * Refresh function to update the object with the database values
         */
        function Refresh();
        
    }

?>