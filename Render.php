<?php

class Render {
    // Renders templates
    static public function template($template_name, $template_parameters=[])
    {
        if(!is_array($template_parameters)) {
            $template_parameters = [];
            error_log("Template parameters for template '$template_name' were invalid, but I fixed it :* ");
        }
    
        // extract() creates new variables from array keys, so if an array has a key "field" then it creates a variable $field
        // so this function is very useful for creating lots of variables, from an entire array of keys
        // we do this here, because we do not know if the template being rendered has zero parameters or one hundred
        // so we can easily pass the required parameters using an array, each key being the variable required by the template
        // and each value of the key, being assigned to the variable it will eventually create
        // extract is sometimes seen as "magic", because it "magics" variables into existence
        // however in this case, its extremely useful, because it allows us to pass an array of any number of keys
        // and not deal with the "fiddly details" of knowing what parameters are needed
        extract($template_parameters, EXTR_SKIP);
    
        // output buffering is very useful, it allows us to grab the entire "output" of a function call, or code block
        // and then put all the printed output into a variable (in this case, $template)
        // this also stops functions from randomly outputting html to the browser, this function acts as a catch-all
        // it catches __EVERYTHING__ and scoops it all up into a buffer
        // then when you call ob_get_clean(), it puts all the content you scooped up, into a variable, nice and simple!
        ob_start();
        include($template_name);
        $template = ob_get_clean();
        
        return $template;
    }
    
    // Renders pages
    static public function page($template,$params=[]){
        $file = __DIR__."/../templates/$template";
    
        if(is_file($file)){
            $template = Render::template($file,$params);
        }
    
        die(Render::template(__DIR__."/../templates/page_skeleton.php", [
            "content" => $template
        ]));
    }
    
    // Renders admin pages
    static public function admin_page($template,$params=[]){
        $file = __DIR__."/../templates/$template";
    
        if(is_file($file)){
            $template = Render::template($file,$params);
        } else {
            $template = "Error: Cannot locate the template '$template'.";
        }
    
        die(Render::template(__DIR__."/../templates/admin_skeleton.php", [
            "content" => $template
        ]));
    }
}