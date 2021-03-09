<!DOCTYPE html>
<html lang="PT-BR">

<head>

    <!-- meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Appslan - One Page App Landing Page">
    <meta name="keywords" content="Apps, Software, technology, landing page, business, responsive, onepage, corporate, clean">
    <meta name="author" content="Coderspoint">

    <!-- Site title -->
    <title>The boss</title>

    <!-- favicon -->
    <link src="{{secure_asset('img/favicon.ico')}}" rel="icon">

    <link href="{{ secure_asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/owl.transitions.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/magnific-popup.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/responsive.css') }}" rel="stylesheet" type="text/css">



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .logo {
            max-width: 160px;
            margin-top: -15px;
        }

        .slider-area {
            background: #3f8ed9;
            min-height: 400px;
        }
    </style>
</head>

<body>

    <!-- Preloader starts-->
    <div id="preloader"></div>
    <!-- Preloader ends -->

    <!-- Navigation area starts -->
    <div class="menu-area navbar-fixed-top">
        <div class="container">
            <div class="row">

                <!-- Navigation starts -->
                <div class="col-md-12">
                    <div class="mainmenu">
                        <div class="navbar navbar-nobg">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="">
                                    <span>
                                        <img class="logo" src="{{secure_asset('img/logo-text.png')}}" alt="">
                                    </span>
                                </a>
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse">
                                <nav>
                                    <ul class="nav navbar-nav navbar-right">
                                        <li class="active"><a class="smooth_scroll" href="#slider">HOME</a></li>
                                        <li><a class="smooth_scroll" href="#contact">CONTATO</a></li>
                                        <li><a href="{{ URL::route('home') }}">ACESSAR</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Navigation ends -->

            </div>
        </div>
    </div>
    <!-- Navigation area ends -->



    <!-- Slider area starts -->
    <section id="slider" class="slider-area grd-bg">


        <div id="carousel-example-generic" class="carousel slide carousel-fade">

            <div class="carousel-inner" role="listbox">

                <!-- Item 1 -->
                <div class="item active">
                    <div class="table">
                        <div class="table-cell">
                            <div class="intro-text">
                                <div class="container">
                                    <div class="row">

                                        <!-- intro image -->
                                        <!-- <div class="col-md-6 col-sm-12 intro-img">
                                            <img src="{{secure_asset('img/slider/1.png')}}" alt="">
                                        </div> -->

                                        <!-- intro text -->
                                        <div class="col-md-12 ">
                                            <div class="intro-text-box clearfix">
                                                <h1>A melhor maneira de gerir suas vendas</h1>
                                                <div class="center"><a href="{{ URL::route('home') }}" class="btn btn-lg btn-white">ACESSAR</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="item">
                    <div class="table">
                        <div class="table-cell">
                            <div class="intro-text">
                                <div class="container">
                                    <div class="row">

                                        <!-- intro image -->
                                        <!-- <div class="col-md-6 col-md-push-6 col-sm-12 intro-img">
                                            <img src="{{secure_asset('img/slider/2.pn')}}g" alt="">
                                        </div> -->

                                        <!-- intro text -->
                                        <div class="col-md-12 ">
                                            <div class="intro-text-box clearfix">
                                                <h1>Conecte-se ao seus clientes</h1>
                                                <div class="center"><a href="{{ URL::route('home') }}" class="btn btn-lg btn-white">ACESSAR</a></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Wrapper for slides-->


                <!-- Carousel Pagination -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                </ol>


                <!-- Slider left right button -->
                <!-- <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <img src="{{secure_asset('img/left-arrow.')}}png" alt="">
            </a>

            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <img src="{{secure_asset('img/right-arrow')}}.png" alt="">
            </a> -->

            </div>

            <!-- bootstrap carousel -->

    </section>
    <!-- Slider area ends -->


    <!-- Contact area starts -->
    <section id="contact" class="contact-area section-big">
        <div class="container">

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2>Contate-nos</h2>
                        <p>Experimente nossos serviços e melhore o gerenciamento e o relacionamento com seus clientes.</p>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- <div class="col-md-6">
                    <div class="contact-form">
                        <form id="ajax-contact" action="<?= URL::route('contact') ?>" method="post">
                            <div class="form-group in_name">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nome" required="required">
                            </div>
                            <div class="form-group in_email">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required="required">
                            </div>
                            <div class="form-group in_message">
                                <textarea rows="5" name="message" class="form-control" id="message" placeholder="Mensagem" required="required"></textarea>
                            </div>
                            <div class="actions">
                                <input type="submit" value="Send" name="submit" id="submitButton" class="btn" title="Enviar">
                            </div>
                        </form>

                        <div id="form-messages"></div>

                    </div>
                </div> -->

                <div class="col-md-6">

                    <div class="address">
                        <h3 class="subtitle">Informações de contato</h3>
                        <div class="address-box clearfix">
                            <p><a href="tel:5514996350585">+55 14 99736-1227</a></p>
                        </div>
                        <div class="address-box clearfix">
                            <p><a href="mailto:adm@inn9.net">adm@inn9.net</a></p>
                        </div>
                        <ul class="social-links">
                            <li><a target="_blank" href="https://www.facebook.com/inn9.chatbots/"><i class="fa fa-facebook"></i></a></li>
                            <li><a target="_blank" href="https://api.whatsapp.com/send?phone=5514997361227&amp;text=Ol%C3%A1,%20vim%20pelo%20site%20de%20voc%C3%AAs..."><i class="fa fa-whatsapp"></i> </a></li>
                            <li><a target="_blank" href="https://www.instagram.com/inn9bots"><i class="fa fa-instagram"></i> </a> </li>
                        </ul>
                    </div>

                </div>


            </div>

        </div>
    </section>
    <!-- Contact area ends -->



    <!-- Footer area starts -->
    <footer class="footer-area">
        <div class="container">
            <p class="text-white">&copy; 2020. Feito com <i class="fa fa-heart"></i> por <a style="color:white!important" href="https://inn9.net" target="_blank">Inn9</p>

        </div>
    </footer>
    <!-- Footer area ends -->

    <!-- Latest jQuery -->
    <script src="{{ secure_asset('js/jquery.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ secure_asset('js/start/bootstrap.min.js') }}"></script>

    <!-- Owl Carousel js -->
    <script src="{{ secure_asset('js/start/owl.carousel.min.js') }}"></script>

    <!-- Mixitup js -->
    <script src="{{ secure_asset('js/start/jquery.mixitup.js') }}"></script>

    <!-- Magnific popup js -->
    <script src="{{ secure_asset('js/start/jquery.magnific-popup.min.js') }}"></script>

    <!-- Waypoint js -->
    <script src="{{ secure_asset('js/start/jquery.waypoints.min.js') }}"></script>

    <!-- Ajax Mailchimp js -->
    <script src="{{ secure_asset('js/start/jquery.ajaxchimp.min.js') }}"></script>

    <!-- Main js-->
    <script src="{{ secure_asset('js/start/main_script.js') }}"></script>

</body>

</html>