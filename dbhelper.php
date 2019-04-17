<?php

/*
Simple Database Object which holds all data required for connecting to a database.
- Such Object automatically opens a connection if it's needed
- When this Object is deconstructed, an open mysql-connection will be automatically closed!
 */
class DBObject {
       private $hostname;
       private $username;
       private $password;

       private $con = NULL;

       private $database;

       public function getDBName(){
               return $this->database;
       }
       
       public function getUserName(){
               return $this->username;
       }

       public function getDBCon(){
               if(!isset($this->con)){
                       $this->connect();
               }
               return $this->con;
       }


       private function connect(){     
               $con = mysql_connect(
                       $this->hostname,
                       $this->username,
                       $this->password
               );
               if(!$con){
                       die('Could not connect: ' . mysql_error()
                               . debug_print_backtrace());
               } else {
                       $this->con = $con;
                       set_time_limit(300);
                       mysql_select_db($this->database, $con);
               }
       }       

       function __construct($hostname, $username, $password, $database){
               $this->hostname = $hostname;
               $this->username = $username;
               $this->password = $password;
               $this->database = $database;
       }

       function __destruct(){
               if(isset($this->con)){
                       mysql_close($this->con);
               }
       }
}


class TableObject {
       private $DBObject;
       
       private $TableName;
       
       private $ColumnTranslations;

       public function __construct($DBObject, $TableName, $ColumnTranslations){
               if(!is_object($DBObject) || !(get_class($DBObject) == 'DBObject')){
                       die("First argument isn't a DBObject!". debug_print_backtrace());
               }
               if(isset($ColumnTranslations) && !is_array($ColumnTranslations)){
                       die("ColumnTranslation isn't an array!" . debug_print_backtrace());
               }
               $this->DBObject = $DBObject;
               $this->TableName = $TableName;
               if(isset($ColumnTranslations)){
                       $this->ColumnTranslations = $ColumnTranslations;
               } else {
                       $this->ColumnTranslations = NULL;
               }
               //var_dump($this->ColumnTranslations);
       }

       public function tableName(){
               return $this->DBObject->getDBName() . '.' . $this->TableName;
       }

       public function long_columnName($columnIdentifier){
               return $this->tableName() . '.' . $this->columnName($columnIdentifier);

       }

       public function columnName($columnIdentifier){
               if(array_key_exists($columnIdentifier, $this->ColumnTranslations)){
                       return $this->ColumnTranslations[$columnIdentifier];
               }
               return $columnIdentifier;
       }
}

?>
