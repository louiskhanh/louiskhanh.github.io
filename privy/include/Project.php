<?php 

/** Get our PAGE define **/
$_p = isset($_GET['p']) ? $_GET['p'] : '/'; 		// Grab our page from htaccess
unset($_GET['p']);									// Unset the un-needed p get variable (to not confuse future code)
$p = explode('/',$_p);								// Explode our page by slash, and store in $p
if($p[count($p)-1] == '') {unset($p[count($p)-1]);} // Remove the last item in the array if its empty (this implies the page had a slash at the end)
$_p = implode('/',$p);								// implode our 'clean' page array, back into the $_p variable,
if($_p == '') {$_p = '/';}							// make sure that our index page can be returned (would return empty string which is the same as false in if statement)
define('PAGE',$_p);									// Then define our normalized PAGE
unset($_p);											// Unset this tmp variable so we can use it safely later

/**
 * This powerful function helps in many places, it handles checking a regexp against the page (PAGE) url, and returns the url, or $return, or false
 * @param (string) $regexp // regexp to check against the page url
 * @param (anything) $return // (including closures, which the $return is then passed the regexp)
 * @author Casey Childers
*/
function p($regexp,$return=null) {
	if(is_string($regexp)) {
		$pm = preg_match($regexp, PAGE, $m)?PAGE:null;
	} else if(is_bool($regexp)) {
		$m = $regexp;
		$pm = $regexp?PAGE:null;
	}
	return ($return && $pm)?is_closure($return)?$return($m):$return:$pm;
}

/** is_closure() is a helper function to solve the is_callable() issue on closures
 *
 *	@param (closure) $t // should be a closure if intending on this function returning true
 *	@return (boolean) // true if $t is a closure type of function, else false
 *  @author Casey Childers
 **/
function is_closure($t) {
	return is_object($t) && ($t instanceof Closure);
}

function prent($var) {
	echo '<pre>'.print_r($var,true).'</pre>';
}

/** Project Class gets our projects going faster by including common functionality
 *		@author Casey Childers
 *		@updated 102920140734AM: Created
 **/
class Project {
	
	public function __construct() {
		
	}
	
	public function ReplaceTags($tpl,$data) {
		if($data) {
			foreach($data AS $r => $k)
				$tpl = str_replace($r, $k, $tpl);
		}
		return $tpl;
	}
	
	public function KeysToTags($array) {
		foreach($array as $k=>$c) {
			$array['{'.strtoupper($k).'}'] = $c;
			unset($array[$k]);
		}
		
		return $array;
	}

	public function View($file, $data = null, $folder = 'view') {
		$tpl = file_get_contents(ROOT . "/include/$folder/$file.html");

		$tpl = $this->ReplaceTags($tpl,$data);

		return $tpl;
	}
	
	public function CSS($files) {
		$buffer = '';
		
		if(isset($files)) {
			foreach($files as $file) {
				if(stristr($file,'http')) {
					$buffer .= file_get_contents($file);
				} else {
					$buffer .= file_get_contents(ROOT . $file);
				}
			}
		}
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		
		// Remove whitespace, tabs, & newlines.
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		
		if($buffer != '') {
			$buffer = "<style>\r\n".$buffer."\r\n\t\t</style>";
		}
		return $buffer;
	}
    public function FONT($files) {
        $buffer = "\r\n\t\t";

        if(isset($files)) {
            foreach($files as $file) {
                $buffer .= '<link rel="stylesheet" type="text/css" href="'.URL.$file.'" />'."\r\n\t\t";
            }
        }
        return $buffer;
    }
	
	public function HTTPCSS($files) {
		$buffer = "\r\n\t\t";
		
		if(isset($files)) {
			foreach($files as $file) {
				if(stristr($file,'http')) {
					$buffer .= '<link rel="stylesheet" type="text/css" href="'.$file.'" />'."\r\n\t\t";
				} else {
					$buffer .= '<link rel="stylesheet" type="text/css" href="'.URL.$file.'" />'."\r\n\t\t";
				}
			}
		}
		
		return $buffer;
	}
	
	public function JS($files) {
		$buffer = '';
	
		if(isset($files)) {
			foreach($files as $file) {
				if(stristr($file,'http')) {
					$buffer .= file_get_contents($file);
				} else {
					$buffer .= file_get_contents(ROOT . $file);
				}
			}
		}
		
		if($buffer != '') {
			$buffer = "<script type=\"text/javascript\">\r\n".$buffer."\r\n\t\t</script>";
		}
		return $buffer;
	}
	
	public function HTTPJS($files) {
		$buffer = '';
		
		if(isset($files)) {
			foreach($files as $file) {
				if(stristr($file,'http')) {
					$buffer .= '<script language="JavaScript" type="text/javascript" src="'.$file.'"></script>'."\r\n\t\t";
				} else {
					$buffer .= '<script language="JavaScript" type="text/javascript" src="'.URL.$file.'"></script>'."\r\n\t\t";
				}
			}
		}
		
		return $buffer;
	}
	
	public function CryptoRandSecure($min, $max) {
		$range = $max - $min;
		if ($range < 0) return $min; // not so random...
		$log = log($range, 2);
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}
	
	public function GetToken($length) {
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->CryptoRandSecure(0,strlen($codeAlphabet))];
		}
		return $token;
	}
	
	public function TimeSince($time) {
		$time = time() - $time; // to get the time since that moment
		
		$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
		);
		
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}
	}

}

/** Start our Project **/
$Project = new Project();
?>