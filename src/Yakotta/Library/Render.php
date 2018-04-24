<?php
namespace Yakotta\Library;

class Render {
    // Renders templates
    static public function template($template_name, $template_parameters=[])
    {
        if(!is_array($template_parameters)) {
            $template_parameters = [];
            error_log("Template parameters for template '$template_name' were invalid, but I fixed it :* ");
        }
    
        extract($template_parameters, EXTR_SKIP);
        ob_start();
        include($template_name);
        $template = ob_get_clean();
        
        return $template;
    }
    
    // Renders pages
    static public function page($template,$params=[]){
        $file = __DIR__."/../templates/$template";
    
        if(is_file($file)){
            die(Render::template(__DIR__."/../templates/page_skeleton.php", [
                "content" => Render::template($file,$params)
            ]));
        } else {
            die(Render::template(__DIR__."/../templates/error_404.php", [
                "error" => "Error 404: '$template' page not found :("
            ]));
        }
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