<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="<?php echo base_url();?>assets/fo/img/fav-icon.png" type="image/x-icon" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Persuit</title>

    <!-- Icon css link -->
    <link href="<?php echo base_url();?>assets/fo/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/vendors/line-icon/css/simple-line-icons.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/vendors/elegant-icon/style.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/fo/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Rev slider css -->
    <link href="<?php echo base_url();?>assets/fo/vendors/revolution/css/settings.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/vendors/revolution/css/layers.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/vendors/revolution/css/navigation.css" rel="stylesheet">
    
    <!-- Extra plugin css -->
    <link href="<?php echo base_url();?>assets/fo/vendors/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/vendors/bootstrap-selector/css/bootstrap-select.min.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>assets/fo/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/fo/css/responsive.css" rel="stylesheet">

    <style>
        .spinftw {
            border-radius: 100%;
            display: inline-block;
            height: 30px;
            width: 30px;
            top: 50%;
            position: absolute;
            -webkit-animation: loader infinite 2s;
            -moz-animation: loader infinite 2s;
            animation: loader infinite 2s;
            box-shadow: 25px 25px #3498db, -25px 25px #c0392b, -25px -25px #f1c40f, 25px -25px #27ae60;
            background-size: contain;
        }

        @-webkit-keyframes loader {
            0%,
            100% {
                box-shadow: 25px 25px #3498db, -25px 25px #c0392b, -25px -25px #f1c40f, 25px -25px #27ae60;
            }
            25% {
                box-shadow: -25px 25px #3498db, -25px -25px #c0392b, 25px -25px #f1c40f, 25px 25px #27ae60;
            }
            50% {
                box-shadow: -25px -25px #3498db, 25px -25px #c0392b, 25px 25px #f1c40f, -25px 25px #27ae60;
            }
            75% {
                box-shadow: 25px -25px #3498db, 25px 25px #c0392b, -25px 25px #f1c40f, -25px -25px #27ae60;
            }
        }

        @-moz-keyframes loader {
            0%,
            100% {
                box-shadow: 25px 25px #3498db, -25px 25px #c0392b, -25px -25px #f1c40f, 25px -25px #27ae60;
            }
            25% {
                box-shadow: -25px 25px #3498db, -25px -25px #c0392b, 25px -25px #f1c40f, 25px 25px #27ae60;
            }
            50% {
                box-shadow: -25px -25px #3498db, 25px -25px #c0392b, 25px 25px #f1c40f, -25px 25px #27ae60;
            }
            75% {
                box-shadow: 25px -25px #3498db, 25px 25px #c0392b, -25px 25px #f1c40f, -25px -25px #27ae60;
            }
        }

        @keyframes loader {
            0%,
            100% {
                box-shadow: 25px 25px #3498db, -25px 25px #c0392b, -25px -25px #f1c40f, 25px -25px #27ae60;
            }
            25% {
                box-shadow: -25px 25px #3498db, -25px -25px #c0392b, 25px -25px #f1c40f, 25px 25px #27ae60;
            }
            50% {
                box-shadow: -25px -25px #3498db, 25px -25px #c0392b, 25px 25px #f1c40f, -25px 25px #27ae60;
            }
            75% {
                box-shadow: 25px -25px #3498db, 25px 25px #c0392b, -25px 25px #f1c40f, -25px -25px #27ae60;
            }
        }

        .bounce {
            -webkit-animation:bounce 1s infinite;
        }

        @-webkit-keyframes bounce {
            0%       { bottom:5px; }
            25%, 75% { bottom:15px; }
            50%      { bottom:20px; }
            100%     {bottom:0;}
        }

        #CssLoader
        {
            text-align: center;
            height: 100%;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(49, 58, 56, 0.85);
            z-index: 9999;
        }

        .pagination_area .pagination li a {
            border: 1px solid #131313fc;
        }

    </style>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url();?>assets/fo/js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>assets/fo/js/popper.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/js/bootstrap.min.js"></script>
    <!-- Rev slider js -->
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.video.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <!-- Extra plugin css -->
    <script src="<?php echo base_url();?>assets/fo/vendors/counterup/jquery.waypoints.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/counterup/jquery.counterup.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/bootstrap-selector/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/image-dropdown/jquery.dd.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/js/smoothscroll.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/isotope/imagesloaded.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/isotope/isotope.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/magnify-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/vertical-slider/js/jQuery.verticalCarousel.js"></script>
    <script src="<?php echo base_url();?>assets/fo/vendors/jquery-ui/jquery-ui.js"></script>
    
    <script src="<?php echo base_url();?>assets/fo/js/theme.js"></script>

    <script>
        const baseUrl = "<?= base_url();?>";
    </script>
</head>