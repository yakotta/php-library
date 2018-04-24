<?php

class Debug {
    // Preformats the var dump function
    static public function dump()
    {
        $args = func_get_args();
        
        print("<pre>");
        call_user_func_array("var_dump",$args);
        print("</pre>");
    }
}
