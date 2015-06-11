<?php
/**
 * Copyright (C) 2011 PrivateDaddy.com
 *
 * PrivateDaddy is free software; see http://www.privatedaddy.com/ for details.
 */
class PrivateDaddy
{
	function PrivateDaddy() {
		$this->token = $this->generate_token();
	}

	function generate_token()
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		$string = '';

		for ($i = 0; $i < 4; $i++)
		{
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars[$pos];
		}

		return $string;
	}

	function get_base_for_javascript() {
		return $this->string_to_hex($this->base, '%');
	}

	function get_token_for_javascript() {
		return $this->string_to_hex($this->token, '%');
	}

	function xor_encrypt($input, $key){

		$key_length = strlen($key);

		for ($i = 0; $i < strlen($input); $i++){

			$char_pos = $i % $key_length;

			$encrypted_char = ord($input[$i]) ^ ord($key[$char_pos]);

			$input[$i] = chr($encrypted_char);
		}

		return $input;
	}

	function string_to_hex($input, $prefix = '') {

		$result = '';

		for ($i = 0; $i < strlen($input); $i++)
		{
			$char_hex = dechex(ord($input[$i]));

			$char_hex = str_pad($char_hex, 2, '0', STR_PAD_LEFT);

			$result = $result . $prefix . $char_hex;
		}

		return $result;
	}

	function obfuscate($href) {
		$token = $this->token;

		$encrypted = $href;
		$encrypted = $this->xor_encrypt($encrypted, $this->base);
		$encrypted = $this->xor_encrypt($encrypted, $this->token);

		$mash = $encrypted . $token;
		$mash = base64_encode($mash) . '_' . $this->site_id;
		$mash = urlencode($mash);
		$mash = str_replace('%', '-', $mash);

		return 'http://www.privatedaddy.com/?q=' . $mash;
	}

	function obfuscate_hrefs($input) {
		$href_regex = '/\<a[^\/\>]*href=["|\']((mailto|feedback):[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})["|\']/Ui';

		preg_match_all($href_regex, $input, $out, PREG_PATTERN_ORDER);

		$result = $input;

		for($i = 0; $i < sizeof($out[1]); $i++) {
			$href = $out[1][$i];

			$obfuscated_href = $this->obfuscate($href);

			$result = str_ireplace($href, $obfuscated_href, $result);
		}

		return $result;
	}

	function obfuscate_elements_content($input) {
		$elements_regex = '/\>([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\</Ui';

		preg_match_all($elements_regex, $input, $out, PREG_PATTERN_ORDER);

		$result = $input;

		for($i = 0; $i < sizeof($out[1]); $i++) {
			$content = $out[1][$i];

			$obfuscated_content = $this->obfuscate($content);

			$result = str_ireplace('>' . $content . '<', '>&lt;' . $obfuscated_content . '&gt;<', $result);
		}

		return $result;
	}

	function ob_callback($input, $status) {
		$result = $input;

		$result = $this->obfuscate_hrefs($result);

		$result = $this->obfuscate_elements_content($result);

		return $result;
	}

	function start() {
		ob_start(array(&$this, 'ob_callback'));
	}

	var $token;

	//Do NOT change the lines below - GRACEFUL DEGRADATION CAPABILITIES WILL CEASE TO WORK
	var $base = 'V3dPoqeCJjLd1eymhBakmWYlFuXU1OcNIgsXwxq6G1ay5yLd0Mocyco4xbPoQR1p';
	var $site_id = '462';
	//Do NOT change the lines above - GRACEFUL DEGRADATION CAPABILITIES WILL CEASE TO WORK
}

$privatedaddyObj = new PrivateDaddy();
$privatedaddy = &$privatedaddyObj;
?>
<script type="text/javascript">
//<![CDATA[
	//PrivateDaddy - http://www.privatedaddy.com/
	var PrivateDaddy=new Object();PrivateDaddy.chainFunctions=function(a,b){return function(){if(a){a()}if(b){b()}}};PrivateDaddy.xorDecrypt=function(a,b){var i=0;var d='';var e;for(i=0;i<a.length;i++){var c=a.charCodeAt(i);e=b.charCodeAt(i%b.length);d+=String.fromCharCode(c^e)}return d};PrivateDaddy.base64Decode=function(a){var b="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c="";var d,chr2,chr3;var e,enc2,enc3,enc4;var i=0;a=a.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(i<a.length){e=b.indexOf(a.charAt(i++));enc2=b.indexOf(a.charAt(i++));enc3=b.indexOf(a.charAt(i++));enc4=b.indexOf(a.charAt(i++));d=(e<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;c=c+String.fromCharCode(d);if(enc3!==64){c=c+String.fromCharCode(chr2)}if(enc4!==64){c=c+String.fromCharCode(chr3)}}return c};PrivateDaddy.unobfuscate=function(a){var b=a.substring(a.lastIndexOf('?q=')+3);b=b.replace(/[-]/g,'%');b=unescape(b);b=b.substring(0,b.lastIndexOf('_'));b=PrivateDaddy.base64Decode(b);b=b.substring(0,b.length-4);b=PrivateDaddy.xorDecrypt(b,PrivateDaddy.token);b=PrivateDaddy.xorDecrypt(b,PrivateDaddy.base);return b};PrivateDaddy.pageHandler=function(){var a=document.getElementsByTagName('*');for(var i=0;i<a.length;i++){if(a[i].innerHTML){var b=a[i].innerHTML;if(b.indexOf('http://www.privatedaddy.com')<=4&&b.indexOf('http://www.privatedaddy.com')!==-1&&b.indexOf('?q=')!==-1){a[i].innerHTML=PrivateDaddy.unobfuscate(b.substring(4,b.length-4))}}}var c=document.getElementsByTagName('a');for(i=0;i<c.length;i++){var d=c[i].href;if(d.indexOf('http://www.privatedaddy.com')===0&&d.indexOf('?q=')!==-1){c[i].href=PrivateDaddy.unobfuscate(d)}}};PrivateDaddy.base=unescape('<?php echo $privatedaddy->get_base_for_javascript(); ?>');PrivateDaddy.token=unescape('<?php echo $privatedaddy->get_token_for_javascript(); ?>');PrivateDaddy.addOnloadHandler=function(a){if(window.addEventListener){window.addEventListener('load',a,false)}else if(window.attachEvent){window.attachEvent('onload',function(){return a.apply(window,new Array(window.event))})}else{window.onload=PrivateDaddy.chainFunctions(window.onload,a)}};PrivateDaddy.addOnloadHandler(PrivateDaddy.pageHandler);
// ]]>
</script>
<?php
$privatedaddy->start();
?>