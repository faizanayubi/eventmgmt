<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>500 Not Found | SwiftMVC Framework</title>

        <!-- Favicons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="public/assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="shortcut icon" href="public/assets/ico/favicon.ico">

        <!-- CSS Global -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="/public/assets/css/main.css" rel="stylesheet">
        
        <!--[if lt IE 9]>
        <script src="/public/assets/plugins/iesupport/html5shiv.js"></script>
        <script src="/public/assets/plugins/iesupport/respond.min.js"></script>
        <![endif]-->
    </head>
    <body id="home" class="wide body-light error-page">

        <!-- Wrap all content -->
        <div class="wrapper">

            <!-- HEADER -->
            <header class="header fixed">
                <div class="container">
                    <div class="header-wrapper clearfix">

                        <!-- Logo -->
                        <div class="logo">
                            <a href="/" class="scroll-to">
                                <span class="fa-stack">
                                    <i class="fa logo-hex fa-stack-2x"></i>
                                    <i class="fa logo-fa fa-map-marker fa-stack-1x"></i>
                                </span>
                                MyEvent Group
                            </a>
                        </div>
                        <!-- /Logo -->

                        <!-- Navigation -->
                        <div id="mobile-menu"></div>
                        <nav class="navigation closed clearfix">
                            <a href="#" class="menu-toggle btn"><i class="fa fa-bars"></i></a>
                            <ul class="sf-menu nav">
                                <li><a href="/">Home</a></li>
                            </ul>
                        </nav>
                        <!-- /Navigation -->

                    </div>
                </div>
            </header>
            <!-- /HEADER -->

            <!-- Content area -->
            <div class="content-area">

                <div id="main">
                    <!-- SLIDER -->
                    <section class="page-section no-padding color">
                        <div class="container">

                            <div id="main-slider">

                                <!-- Slide -->
                                <div class="item page text-center slide0">
                                    <div class="caption">
                                        <div class="container">
                                            <div class="div-table">
                                                <div class="div-cell">
                                                    <div class="caption-subtitle" data-animation="fadeInUp" data-animation-delay="300"><i class="fa fa-warning"></i></div>
                                                    <h3 class="caption-subtitle" data-animation="fadeInUp" data-animation-delay="300">Error 404</h3>
                                                    <h2 class="caption-title" data-animation="fadeInDown" data-animation-delay="100">Page not Found</h2>
                                                    <p class="caption-text">
                                                        <a class="btn btn-theme btn-theme-xl scroll-to" href="/" data-animation="flipInY" data-animation-delay="600"> Go to Homepage <i class="fa fa-arrow-circle-right"></i></a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </section>
                    <!-- /SLIDER -->
                </div>

            </div>
            <!-- /Content area -->

        </div>
        <!-- /Wrap all content -->

        <!-- JS Global -->
        <script src="/public/assets/js/main.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script src="/public/assets/js/theme.js"></script>
        <script src="/public/assets/js/public-1.0.js"></script>
    </body>
</html>

<?php if (DEBUG): ?>
    <pre><?php print_r($e); ?></pre>
<?php endif; ?>