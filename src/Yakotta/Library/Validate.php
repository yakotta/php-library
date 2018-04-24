<?php
namespace Yakotta\Library;

class Validate {
    // Verifies if an input is a string
    static public function string($input) {
        if (!is_string($input) || empty($input)) return false;
    
        return true;
    }
    
    // Verifies if an input is a valid email address
    static public function email($input) {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        return true;
    }
    
    // Verifies if an input is a valid phone number
    static public function phone($input) {
        // Possible formats: (408) 375-2798, 408-375-2798, 408 375 2798, 4083752798, 408.375.2798
    
        // A QUICK PRIMER ON REGEX
        // the / and / at the start and end signal that this is a "regex pattern"
        // the ^ at the beginning of the pattern means "it must start here" (there is nothing before this point, this is the start of the string)
        // the $ at the end of the pattern means "it must end here" (there is nothing after this point, this is the end of the string)
        // the [] brackets mean "select a set of characters contained within", e.g. [(] means select a bracket, [0-9] means select any number
        // the {x} where x=3 or 4, means, I want "x" of the previous requested match, in our case [0-9]{3} means, it wants three numbers 123, 345, 567, 001, etc
        // the [)]? the [] and [)] are already explained, but the ? means, it can have 0 or more of them, in other words, its optional
        // the [.\s-] therefore is obvious, including the ? after it, means it wants a literal character "." or a space " ", or a dash/hyphen "-"
        if(preg_match("/^[(]?[0-9]{3}[)]?[.\s-]?[0-9]{3}[.\s-]?[0-9]{4}$/",$input)){
            return true;
        }
        
        return false;
    }
    
    /**
     * Checks to see if all input parameters exist and are not empty.
     * 
     * $source - An array of information that you would like to search through
     * $parameters - An array of parameter information, relating to what you are looking for and under what conditions
     * 
     * Notes:
     *  - The source parameter might be altered during this function call, because if it failed validation, 
     *    but was not required to succeed, the field will be set to an empty string
     * 
     * returns:
     *  - Boolean true, if the parameters all exist within their constraints
     *  - String containing the name of the field which has failed "validation"
     */
    static public function parameters (&$source, $parameters=[])
    {
        foreach($parameters as $field => $rules)
        {
            if(!array_key_exists($field, $source) || empty($source[$field])){
                if($rules["required"] === true) {
                    return $field;
                }
            }
            
            if($rules["type"] === "string") {
                if (Validate::string($source[$field]) === false) {
                    if($rules["required"] === true) {
                        return $field;
                    }
                    
                    $source[$field] = "";
                }
            }
            
            if($rules["type"] === "email") {
                if (Validate::email($source[$field]) === false) {
                    if($rules["required"] === true) {
                        return $field;
                    }
                    
                    $source[$field] = "";
                };
            }
            
            if($rules["type"] === "phone") {
                if (Validate::phone($source[$field]) === false) {
                    if($rules["required"] === true) {
                        return $field;
                    }
                    
                    $source[$field] = "";
                };
            }
            
            if($rules["type"] === "file") {
                if(array_key_exists("options", $rules) && is_array($rules["options"]) && !empty($rules["options"]))
                {
                    $filetype = $source[$field]["type"];
                    $found = false;
                    
                    foreach ($parameters[$field]["options"] as $option) {
                        if ($filetype === $option) {
                            $found = true;
                        }
                    }
                    
                    if($found === false){
                        if($rules["required"] === true){
                            return $field;
                        }
                        
                        $source[$field] = "";
                    }
                } else {
                    die("You didn't specify a desired file type so I killed you.");
                }
            }
        }
    
        return true;
    }   
}