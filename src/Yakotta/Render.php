<?php
namespace Yakotta;

class Render {
    static public $templateDir = false;
    
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
        $file = self::$templateDir."/$template";
    
        if(is_file($file)){
            die(Render::template(self::$templateDir."/page_skeleton.php", [
                "content" => Render::template($file,$params)
            ]));
        } else {
            die(Render::template(self::$templateDir."/error_404.php", [
                "error" => "Error 404: '$template' page not found :("
            ]));
        }
    }
    
    // Renders admin pages
    static public function admin_page($template,$params=[]){
        $file = self::$templateDir."/$template";
    
        if(is_file($file)){
            $template = Render::template($file,$params);
        } else {
            $template = "Error: Cannot locate the template '$template'.";
        }
    
        die(Render::template(self::$templateDir."/admin_skeleton.php", [
            "content" => $template
        ]));
    }
}