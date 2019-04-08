<?php
    namespace BasicORM\LOGS;

    define("MAIN_LOG", "SAGWEB.log");
    
    class Log{
        /**
         * Writes lines in the file log
         */
        public static function WriteLog($filePath, $lines = []){
            $file = fopen($filePath,"a+");
            $date = date_format(new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires')), 'Y-m-d H:i:s');
            for($i=0; $i<count($lines); $i++){
                fwrite($file, "$date: ".$lines[$i].PHP_EOL);
            }
            fclose($file);
        }
    }
?>