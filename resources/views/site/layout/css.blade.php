<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined">
<link rel="stylesheet" href="{{ asset('asset/css/libs/swiper.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/libs/material-components-web.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/style.css') }}?v1.2">
<link rel="stylesheet" href="{{ asset('asset/css/skins/blue.css') }}?v1.0">
@if(app()->getLocale() === 'ar')
<link rel="stylesheet" href="{{ asset('asset/css/rtl.css') }}?v1">
@endif
<link rel="stylesheet" href="{{ asset('asset/css/responsive.css') }}">

<link rel="stylesheet" href="{{ asset('asset/css/app.css?v4') }}?v2">
<link rel="stylesheet" href="{{ asset('asset/css/custom.css?v3') }}?v2">

<style>
    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    footer::before {
        background-image: url("/images/main/footer.jpg") !important;
    }

    .erfheight .multiselect__content-wrapper {
        max-height: 100vh !important;
    }

    .price-search-btn {
        border: 1px solid gray;
        border-radius: 22px;
        padding: 5px 10px;
        background: #fff;
        color: #000;
        margin: 1rem 0 0.8rem;
        display: inline-block;
        text-decoration: none;
        text-transform: uppercase;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .justify-content-end {
        justify-content: end !important;
    }


    .modal-container>a {
        display: block;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .md-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 50%;
        max-width: 500px;
        min-width: 320px;
        height: auto;
        z-index: 2000;
        visibility: hidden;
        transform: translateX(-50%) translateY(-50%);
    }

    .md-modal:target {
        visibility: visible;
    }

    .md-overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        visibility: hidden;
        top: 0;
        left: 0;
        z-index: 1000;
        opacity: 0;
        background: rgba(0, 0, 0, 0.8);
        transition: all 0.3s;
    }

    .md-modal:target~.md-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* Content styles */
    .md-content {
        position: relative;
        border-radius: 3px;
        margin: 0 auto;
        background: white;
    }

    .md-content h3 {
        margin: 0;
        padding: 0.4em;
        text-align: center;
        font-size: 2.4em;
        font-weight: 300;
        opacity: 0.8;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 3px 3px 0 0;
    }

    .md-content>div {
        padding: 15px 40px 30px;
        margin: 0;
        font-weight: 300;
        font-size: 1.15em;
    }

    .md-content>div p {
        margin: 0;
        padding: 10px 0;
    }

    .md-content>div ul {
        margin: 0;
        padding: 0 0 30px 20px;
    }

    .md-content>div ul li {
        padding: 5px 0;
    }

    .modal-container .input {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1px solid #80808047;
        border-radius: 10px;
        font-size: 17px;
    }

    /* Effect */

    .md-modal .md-content {
        -webkit-transform: scale(0.7);
        -moz-transform: scale(0.7);
        -ms-transform: scale(0.7);
        transform: scale(0.7);
        opacity: 0;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
    }

    .md-modal:target .md-content {
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
        opacity: 1;
    }

    @media screen and (max-width: 580px) {
        .md-content>div {
            padding: 15px 12px 12px;
            margin: 0;
            font-weight: 300;
            font-size: 1.15em;
        }

        .md-content {
            color: #fff;
            background: #ffffff;
            position: relative;
            border-radius: 25px 25px 0 0;
            margin: 0 auto;
        }

        .md-modal {
            position: fixed;
            top: 100%;
            left: 50%;
            width: 100%;
            max-width: 100%;
            min-width: 320px;
            height: auto;
            z-index: 2000;
            visibility: hidden;
            transform: translateX(-50%) translateY(-100%);
        }
    }
</style>
