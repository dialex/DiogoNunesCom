<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="../bin/images/icons/favicon.ico" />
<link href="/bin/res/style-plain.css.php" rel="stylesheet" type="text/css" />
<title>Diogo Nunes | Error</title>
</head>

<body>
<div align="center">
  <a href="http://www.diogonunes.com"><img border="0px" src="http://diogonunes.com/bin/images/headerw.png" width="398" height="104" alt="DECORATION logo Diogo Nunes" /><a>
</div>
<?php

$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
        403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
        404 => array('404 Not Found', 'The document or file requested was not found.'),
        405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
        408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
        500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
        502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
        504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.')
        );
        
$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
    $message = 'Unknown error code.';
}
echo '<h1>' , $title , '</h1>' ,"\n", 
     '<p>' , $message , '</p>';
?>
<p>Pedimos desculpa, pois <strong><em>ocorreu um erro</em></strong> ao tentar aceder à página que indicou. Escreveu o endereço correctamente?</p>
<p><strong>Experimente visitar a página principal em <a href="http://www.diogonunes.com">www.diogonunes.com</a></strong></p>
</body>
</html>