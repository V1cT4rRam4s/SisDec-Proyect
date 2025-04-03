<?php
require_once('database/loginMDL.php');
$login = new Login();
if (!$login->isLogin()) {
    header("Location: index.php");
    exit;
};

require_once('views\modal\modal_user.php');
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="chrome">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Victor E. Ramos García Regal">
    <meta name="generator" content="SISDEC">

    <title>SISDEC - <?php echo date('Y'); ?> </title>
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="public/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="public/assets/icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="public/assets/css/sisdec_style.css">
    <script src="public/assets/js/resourse/jquery.js"></script>
    <script src="public/assets/js/resourse/bootstrap.bundle.min.js"></script>
    <script src="public/assets/js/resourse//lodash.min.js"></script>
</head>

<nav class="navbar navbar-expand-lg navbar-dark px-2" style="background-color:<?php echo $_SESSION['perfil_color']; ?>">
    <div class="container-fluid">
        <i class="col-auto bg-light rounded rounded-5 px-2 py-2 d-flex align-items-center">
            <img src="public/assets/img/Logo-Maki.png" alt="LogoMaki" width="45" height="25">
            <h6 class="mt-2 text-dark fw-bold">SISDEC</h6>
        </i>

        <!-- Navbar de acuerdo al Perfil -->
        <?php require_once("views/navbar/navbar_" . $_SESSION['id_perfil'] . ".php"); ?>

        <div class="btn-group shadow-none dropstart" id="userNavDrop">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img id="profileImage"
                    src="public/assets/img<?php echo $_SESSION['url']; ?><?php echo $_SESSION['foto']; ?>"
                    class="rounded-circle" height="65" alt="" loading="lazy" />
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" onclick="modal_data(<?php echo $_SESSION['user_id']; ?>)"><i
                            class="bi bi-key"></i>&nbsp;&nbsp;Editar Perfil</a></li>
                <li class="bg-danger"><a class="dropdown-item" onclick="closeSesion()"><i
                            class="bi bi-power"></i>&nbsp;&nbsp;Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="row g-0 bg-light shadow-lg">
    <div class="col ms-4 mt-2">
        <i class="bi bi-speedometer2"></i>
        <?php echo $_SESSION['perfil_sigla'] . " / "; ?>
        <span id="link_name" name="link_name"></span>
    </div>
    <div class="col me-2 mb-1 mt-2 justify-align-content-end text-end">
    </div>
    <div class="col-auto pe-4 me-4 mt-1 justify-align-content-end text-end" id="status">
        <i class="bi bi-bell-fill fs-6"></i>
    </div>
</div>

<body>
    <div class="container-fluid py-2">
        <div class="bg-light pb-2 mb-3 rounded-3 shadow-lg">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow text-secondary mt-2" style="width: 3rem; height: 3rem;" role="status"
                        id="spiner_general" name="spiner_general">
                    </div>
                </div>
                <div id="containerHTML">
                </div>
            </div>
        </div>
    </div>
</body>

<div class="container-fluid py-2">
    <footer class="bg-light pb-2 py-2 rounded-3 shadow-lg">
        <div class="row">
            <div class="col-md-4 mt-2 d-flex justify-content-center">
                <p class="text-body-secondary" style="font-size: 15px;">
                    &copy;SISDEC
                </p>
            </div>
            <div class="col-md-4 mt-2 d-flex justify-content-center">
                <a href="/" class="link-body-emphasis text-decoration-none">
                    <img src="public/assets/img/Logo-Maki.png" alt="Logo-sisdec" width="75">
                </a>
            </div>
            <div class="col-md-4 mt-2 d-flex justify-content-center">
                <p class="text-body-secondary" style="font-size: 15px;">&copy;MakiSis - <?php echo date("Y"); ?></p>
            </div>
        </div>
    </footer>
</div>

<script src="public/assets/js/resourse/sweetalert2.all.min.js"></script>
<script>
var modal_user = new bootstrap.Modal('#modal_User');
let user_id = Number("<?php echo $_SESSION['user_id']; ?>");
let nivel = Number("<?php echo $_SESSION['nivel']; ?>");
let perfil_user = Number("<?php echo $_SESSION['id_perfil']; ?>");
let url_perfil = "<?php echo $_SESSION['url']; ?>";
let color_perfil = "<?php echo $_SESSION['perfil_color']; ?>";

$(document).ready(function() {
});

function loadContent(link, newLink) {
    $('#containerHTML').off();
    $.ajax({
        url: link,
        type: 'POST',
        data: {},
        beforeSend: function() {
            $('#spiner_general').removeClass('d-none');
            $('#containerHTML').html('<div></div>');
        },
        success: function(data) {
            $('#containerHTML').html(data);
            $('#link_name').text(newLink);

        },
        error: function(xhr) {
            const errorPages = {
                401: '/public/401.php',
                403: '/public/403.php',
                404: '/public/404.php',
                500: '/public/500.php'
            };
            window.location.href = errorPages[xhr.status] || errorPages[500];
        },
        complete: function() {
            $('#spiner_general').addClass('d-none');
        }
    });
};

function closeSesion() {
    $.ajax({
        url: '/database/loginCTR.php',
        type: 'POST',
        data: {
            accion: 'logout'
        },
        dataType: 'json',
        success: function(data) {
            if (data.valor === '0') {
                index_alert.fire({
                    icon: 'error',
                    title: data.text
                });
                audio_error.play();
            };
            if (data.valor === '1') {
                window.location.href = '/index.php';
            };
        }
    });
};
</script>
<script src="public/assets/js/fnc_Alerts.js"></script>
<script src="public/assets/js/fnc_user.js"></script>
<script src="public/assets/js/fnc_paginar.js"></script>
<script src="public/assets/js/fnc_textSearch.js"></script>
