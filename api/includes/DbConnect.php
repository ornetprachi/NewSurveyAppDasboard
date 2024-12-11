<?php

class DbConnect
{
    //Variable to store database link
    private $con;
    private $serverName;
    private $databaseName;
    private $userName;
    private $password;
    private $connectionString;
 
    //Class constructor
    function __construct()
    {
 
    }

    //This method will connect to the database
    function connect_db_user()
    {
        //Including the constants.php file to get the database constants
        include_once dirname(__FILE__) . '/Constants.php';
 
        try  
        {  
            $this->serverName = DB_HOST_USER;
            $this->databaseName = DB_NAME_USER;
            $this->userName = DB_USERNAME_USER;
            $this->password = DB_PASSWORD_USER;

            $this->connectionString = array("Database"=> $this->databaseName, "CharacterSet" => "UTF-8",   
                    "Uid"=> $this->userName, "PWD"=>$this->password);

            //connecting to sql database
            $this->con = sqlsrv_connect($this->serverName, $this->connectionString); 
     
            //Checking if any error occured while connecting

            if ($this->con == false) {
               die(print_r( sqlsrv_errors(), true));
                // sqlsrv_errors();
                return null;
            }

         }  
        catch(Exception $e)  
        {  
            echo("Error!");  
        }  
 
        //finally returning the connection link
        return $this->con;

    }
 
}


?>