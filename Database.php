<?php
/**
 * class: Database
 * 
 * A class which controls access to our database
 * 
 * help: https://phpdelusions.net/pdo
 */
class Database
{
    // Introduces the $connection member variable
    public $connection;
    
    // 4 functions which establish which connection parameters to use, depending on environment
    private function connectDocker()
    {
        return [getenv("MYSQL_HOST"),"root","root","luxemanagers",3306];
    }
    
    private function connectAntimatterServer()
    {
        return ["localhost","luxemanagers","j4z132gs63c0y8hr","luxemanagers",3306];
    }
    
    private function connectC9()
    {
        return [getenv('IP'),getenv('C9_USER'),"","luxemanagers",3306];
    }
    
    private function connectMAMP()
    {
        return ["localhost","root","root","luxemanagers",3306];
    }

    // Object constructor. Here 
    public function __construct()
    {
        // Determines what environment we're in
        if(getenv("IS_DOCKER")){
            $params = $this->connectDocker();
        }else if(strpos(__DIR__,"clients.antimatter-studios.com") !== false) {
            $params = $this->connectAntimatterServer();
        }else if(getenv("C9_HOSTNAME")) {
            $params = $this->connectC9();
        }else {
            $params = $this->connectMAMP();
        }

        // Takes the parameters and quickly puts them into easy-to-use variables
        list($hostname,$username,$password,$database,$port) = $params;
        
        // Database connnection string, it's how the database knows how to connect
        $dsn = "mysql:dbname=$database;host=$hostname;port=$port;charset=utf8";

        // Creates new PDO, which is the actual connection to the database using database credentials
        $this->connection = new PDO($dsn,$username,$password,[
            PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,  // throw an exception when you have an error
            PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC         // return associative arrays from the database
        ]);    
    }

    // This papers over the fact that we changed how the db connection is formed, and preserves old functionality
    static public function connect()
    {
        return (new Database())->connection;
    }
}