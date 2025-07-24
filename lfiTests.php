<?php if(php_sapi_name() != "cli") die("This is a CLI script");

	function getInput(){
        	$fd = fopen("php://stdin", "r");
        	$ret = trim(fgets($fd));
        	fclose($fd);
        	return $ret;
	}
	
	// ==========SNIP================
	/*                                                                                               
         * This function shall be used very delibritly.                                                  
         * At any given time one of the two inputs shall be static.
	 * It throws and exception on LFI, returns false on no file,
	 * and returns the concatonated absolute path on success.
         * */
	function security_lfi_check($restrictedWebRoot, $dirtyFileName){
		$lute = $restrictedWebRoot.$dirtyFileName;
		$abso = realpath($lute);
		if(!file_exists($lute) && !file_exists($abso))
			return false;
		else if($abso !== $lute)
			throw new Exception("Local File Inclusion Detected!\n\t$abso !== $lute\n");
		
		return $abso;
	}
	// ==========ENDSNIP================

	echo "\n\n\n=======Welcome!++++++\n";

	$AbsoluteWebroot = getcwd()."/sandbox/";
	echo "You are sandboxed in ".$AbsoluteWebroot."\n\nYour goal is to access ".getcwd()."/restricted/passwd\n\n";
	echo "Give it a go, enter a file to read > ";
	$evilPath = getInput();

echo "\n\n\n";

	$safePath = security_lfi_check($AbsoluteWebroot, $evilPath);
	if($safePath === false){
		die("File doesn't exist.\n");
	}
	$content = file_get_contents($safePath);
	echo $content."\n\n";
?>
