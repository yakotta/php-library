<?php
namespace Yakotta\Library;

class Route {
    // Redirect function
    static public function redirect($url) {
        header("Location: $url");
        die("Waiting to redirect to '$url'");
    }

    // Rewrites a requested url based on website location 
    static public function rewrite_url($url) {
        $base = str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__DIR__));
        $base = str_replace("/resources", "", $base);
        // Make sure the url begins with /
        $rewritten_url = "/".$base.$url;
        // Make sure any double slashes // are replaced with single slash /
        $rewritten_url = str_replace("//","/",$rewritten_url);
    
        return $rewritten_url;
    }
    
    // I stole this code from: https://stackoverflow.com/a/30359808/279147
    static public function match($pattern, $callback)
    {    
        if(!is_callable($callback)){
            throw new Exception("Callback passed to match route must be valid");
        }
    
        if(preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern)){
            throw new Exception("Route pattern was not valid according to the specification");
        }
    
        // Turn "(/)" into "/?"
        $pattern = preg_replace('#\(/\)#', '/?', $pattern);
    
        // Create capture group for ":parameter"
        $allowedParamChars = '[a-zA-Z0-9\_\-]+';
        $pattern = preg_replace(
            '/:(' . $allowedParamChars . ')/',   # Replace ":parameter"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );
    
        // Create capture group for '{parameter}'
        $pattern = preg_replace(
            '/{('. $allowedParamChars .')}/',    # Replace "{parameter}"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );
    
        // Add start and end matching
        $patternAsRegex = "@^" . $pattern . "$@D";
    
        $continue = true;
    
        $url = $_GET["url"];
    
        if(preg_match($patternAsRegex, $url, $matches)) {
            // Get elements with string keys from matches
            $params = array_intersect_key(
                $matches,
                array_flip(array_filter(array_keys($matches), 'is_string'))
            );
    
            $continue = call_user_func_array($callback,[$params,$url]);
    
            if(!$continue) die();
        }
    
        return $continue;
    }
}