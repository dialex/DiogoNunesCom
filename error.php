<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Diogo Nunes">
    <link rel="shortcut icon" href="/assets/img/favicon.ico">

    <title>Diogo Nunes | Error page</title>
    <meta name="description" content="You shouldn't be here.">
    <!-- Mobile tab color -->
    <meta name="theme-color" content="#2c3e50">
    <meta name="msapplication-navbutton-color" content="#2c3e50">

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/freelancer.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="/assets/fonts/fonts.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-10714206-5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-10714206-5');
    </script>
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">DIOGO NUNES</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="/"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="/blog/">Blog</a>
                    </li>
                    <li class="page-scroll">
                        <a href="/#work">Work</a>
                    </li>
                    <li class="page-scroll">
                        <a href="/#hobbies">Hobbies</a>
                    </li>
                    <li class="page-scroll">
                        <a href="/#about">About</a>
                    </li>
                    <li class="page-scroll">
                        <a href="/#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Portfolio Work Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
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

                        if (array_key_exists($status, $codes)) {
                            $title = $codes[$status][0];
                            $message = $codes[$status][1];
                        }
                        else $title = false;

                        if ($title == false || strlen($status) != 3) {
                            $message = 'Unknown error code.';
                        }

                        echo '<h2>' , $title , '</h2>' ,"\n",
                             '<p>' , $message , '</p>';
                    ?>
                    <br/><hr/><br/>
                    <p>Please return to the <a href="/">homepage</a>.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visible-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>

    <!-- jQuery -->
    <script src="/assets/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/assets/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="/assets/js/classie.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/assets/js/freelancer.js"></script>
</body>

</html>
