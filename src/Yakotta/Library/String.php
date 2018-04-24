<?php
namespace Yakotta\Library;

class String {
    // Removes all spaces and funky characters from a string
    // https://github.com/christhomas/amslib/blob/master/Amslib_String.php#L61
    static public function slugify($string, $slug = '-', $extra = null)
    {
    	$string		=	String::slugify_translit($string,$extra);
    	$string		=	preg_replace('~[^0-9a-z'.preg_quote($extra, '~').']+~i',$slug, $string);
    	//	This part will clean up the end of the filename, before the extension
    	//	But only do it if you find more than one part because there was an extension
    	$parts		=	explode(".",$string);
    	if(count($parts) > 1){
    		$extension	=	array_pop($parts);
    		$string		=	rtrim(implode(".",$parts),$slug).".$extension";
    	}
    	return strtolower(trim($string, $slug));
    }
    
    // Creates a word slug that strips out all funky characters
    static public function slugify_translit($text,$extra=null)
    {
    	$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
    	$text = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $text);
    	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    	$text = preg_replace(array('~[^0-9a-z'.preg_quote($extra,'~').']~i', '~[ -]+~'), ' ', $text);
    	return trim($text, ' -');
    }
    
    // Creates unique file names from input strings
    static public function unique_filename($filename) {
        // Create the prefix to make each filename unique
        $t = microtime(true);
        $micro = sprintf("%06d",($t - floor($t)) * 1000000);
        $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
        $prefix = $d->format("Y-m-d_H.i.s.u");
        
        // Create the final filename from all the parts of the uploaded data and the prefix
        return $prefix . "_" . String::slugify($filename, '-',  '._');
    }
}