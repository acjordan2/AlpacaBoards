<?php
// get 128 pseudorandom bits in a string of 16 bytes

function random(){
	$pr_bits = '';

	// Unix/Linux platform?
	$fp = @fopen('/dev/urandom','rb');
	if ($fp !== FALSE) {
		$pr_bits .= @fread($fp,24);
		@fclose($fp);
	}

	// MS-Windows platform?
	if (@class_exists('COM')) {
		// http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
		try {
			$CAPI_Util = new COM('CAPICOM.Utilities.1');
			$pr_bits .= $CAPI_Util->GetRandom(16,0);

			// if we ask for binary data PHP munges it, so we
			// request base64 return value.  We squeeze out the
			// redundancy and useless ==CRLF by hashing...
			if ($pr_bits) { $pr_bits = md5($pr_bits,TRUE); }
		} catch (Exception $ex) {
			// echo 'Exception: ' . $ex->getMessage();
		}
	}

	if (strlen($pr_bits) < 16) {
		// do something to warn system owner that
		// pseudorandom generator is missing
	}
	return $pr_bits;
}

for($i=0;$i<1000;$i++)
	print (random())."<br />";
?>
