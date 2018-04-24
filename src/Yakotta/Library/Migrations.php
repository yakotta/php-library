<?php
namespace Yakotta\Library;

class Migrations {
    // Checks migrations in the database
    static public function check($m)
    {
        $db = Database::connect();
    
        $m = str_replace($_SERVER["DOCUMENT_ROOT"], "", $m);
    
        $result = $db->query("select filename from migrations where filename = '$m'");
    
        if($result === false) return false;
        
        if($result->num_rows == 0) return false;
        
        return true;
    }
    
    // Adds migrations to the database table 
    static public function add($m)
    {
        $db = Database::connect();
    
        $m = str_replace($_SERVER["DOCUMENT_ROOT"], "", $m);
        
        $db->query("insert into migrations set filename='$m'");
    }
}