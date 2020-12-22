<?php
	include_once('common.php');

	$IPHONE_SYMBOLS = array('-','/',':',';','(',')','$','&','@','"','.',',','?','!','0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z', '!','+');
	$IPHONE_SYMBOLS_ALT = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','-','/',':',';','(',')','$','&','@','"','.',',','?','!','0','1','2','3','4','5','6','7','8','9', '!','.');
	$ALPHA_ONLY = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	$ALPHA_ONLY_ALT = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');


	function passwordForChecksum($checksum, $symbols, $start, $count) {
		$added = 0;
		while ($added < $count) {
			$character = substr($checksum, $start, 2);
			$start += 2;
			$value = hexdec($character);
			$in_range_value = $value % count($symbols);
			$password .= $symbols[$in_range_value];
			$added++;
		}
		return $password;
	}
	
	function checksumForInput($string) {
		return sha1($string);
	}
?>