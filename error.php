<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">

        <title>Diogo Nunes | Error</title>
        <meta name="description" content="Página de erro">

        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="/assets/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="/assets/css/fonts.css">
        <link rel="stylesheet" href="/assets/css/main.css">
        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <?php require_once('./assets/php/base.php'); ?>
    </head>

    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <?php echo $navbar; ?>

        <div class="hero-unit">
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
        </div>

        <p>Pedimos desculpa, pois <strong><em>ocorreu um erro</em></strong> ao tentar aceder à página que indicou. Escreveu o endereço correctamente?</p>
        <p><strong>Visite a página inicial em <a class="btn btn-primary" href="http://www.diogonunes.com">www.diogonunes.com</a></strong></p>

    </body>
</html>
