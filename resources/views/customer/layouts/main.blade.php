<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="/customer/images/icons/favicon.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/fonts/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/MagnificPopup/magnific-popup.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/customer/vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/customer/css/util.css">
    <link rel="stylesheet" type="text/css" href="/customer/css/main.css">
    <!--===============================================================================================-->
</head>
<body class="animsition">


@include('customer.layouts.header')

@if( auth()->user() )
    @include('customer.layouts.cart')
@endif


@yield('content')

@include('customer.layouts.footer')


<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
</div>

<script src="/customer/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/bootstrap/js/popper.js"></script>
<script src="/customer/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/select2/select2.min.js"></script>
<script>
    $(".js-select2").each(function(){
        $(this).select2({
            minimumResultsForSearch: 20,
            dropdownParent: $(this).next('.dropDownSelect2')
        });
    })
</script>
<!--===============================================================================================-->
<script src="/customer/vendor/daterangepicker/moment.min.js"></script>
<script src="/customer/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/slick/slick.min.js"></script>
<script src="/customer/js/slick-custom.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/parallax100/parallax100.js"></script>
<script>
    $('.parallax100').parallax100();
</script>
<!--===============================================================================================-->
<script src="/customer/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<script>
    $('.gallery-lb').each(function() { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled:true
            },
            mainClass: 'mfp-fade'
        });
    });
</script>
<!--===============================================================================================-->
<script src="/customer/vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
<script src="/customer/vendor/sweetalert/sweetalert.min.js"></script>
<script>
    $('.js-addwish-b2').on('click', function(e){
        e.preventDefault();
    });

    $('.js-addwish-b2').each(function(){
        var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
        $(this).on('click', function(){
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-b2');
            $(this).off('click');
        });
    });

    $('.js-addwish-detail').each(function(){
        var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

        $(this).on('click', function(){
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-detail');
            $(this).off('click');
        });
    });

    /*---------------------------------------------*/

    $('.js-addcart-detail').each(function(){
        var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
        $(this).on('click', function(){
            swal(nameProduct, "is added to cart !", "success");
        });
    });

</script>
<!--===============================================================================================-->
<script src="/customer/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
    $('.js-pscroll').each(function(){
        $(this).css('position','relative');
        $(this).css('overflow','hidden');
        var ps = new PerfectScrollbar(this, {
            wheelSpeed: 1,
            scrollingThreshold: 1000,
            wheelPropagation: false,
        });

        $(window).on('resize', function(){
            ps.update();
        })
    });
</script>
<!--===============================================================================================-->
<script src="/customer/js/main.js"></script>
<style>
    html, body {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-size: 15px;
        color: #333;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    body, p, span, a, li, ul, ol, div,
    input, textarea, select, option, button,
    label, small, strong, em {
        font-family: 'Be Vietnam Pro', sans-serif !important;
    }

    .stext-101, .stext-102, .stext-103, .stext-104, .stext-105,
    .mtext-101, .mtext-102, .mtext-103, .mtext-104, .mtext-105, .mtext-106,
    .ltext-101, .ltext-102, .ltext-103, .ltext-104, .ltext-105 {
        font-family: 'Be Vietnam Pro', sans-serif !important;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 700 !important;
        letter-spacing: 0;
    }

    .main-menu > li > a,
    .main-menu-m a,
    .logo,
    .wrap-menu-desktop .main-menu a {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 600 !important;
    }

    .js-name-detail,
    .js-name-b2,
    .product-name,
    .name-product,
    .block2-name,
    .mtext-105,
    .mtext-106 {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 600 !important;
    }

    .product-price,
    .stext-105,
    .mtext-106,
    .ltext-102 {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 700 !important;
    }

    button,
    .btn,
    .flex-c-m,
    .how-pos2,
    .how-pos1,
    .js-addcart-detail {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 600 !important;
    }

    input,
    select,
    textarea,
    .rs1-select2 .select2-container .select2-selection--single,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 400 !important;
    }

    .ltext-103,
    .sec-title,
    .section-title {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        font-weight: 700 !important;
    }
</style>
</body>
</html>
