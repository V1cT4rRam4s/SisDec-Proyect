<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="public/assets/icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="public/assets/css/sisdec_style.css">
    <title>LOGIN - SISDEC</title>
</head>

<style>
body {
    background: url('public/assets/img/background.jpg') no-repeat center center fixed;
    background-size: cover;
}
</style>

<body>
    <?php
    require_once('database/loginMDL.php');
    $login = new Login();
    if ($login->isLogin()) {
        header("Location: main.php");
        exit;
    };
    ?>

    <section class="d-flex flex-column justify-content-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-5 col-md-5 col-lg-5 col-xl-4 my-3 mx-3 bg-secondary rounded rounded-5 ms-auto">
                    <div
                        class="d-flex flex-column align-items-center my-3 mx-3 bg-light bg-opacity-75 rounded rounded-5 p-4">
                        <img src="public/assets/img/Logo-Maki.png" class="img-fluid w-25 vh-25 mt-2" alt="logo-maki">
                        <h1 class="fw-bold">SISDEC</h1>
                        <b class="h6 fw-normal text-center">Sistema de Gestión y Control Documentario Físico con
                            respaldo electrónico Centralizado</b>
                    </div>
                    <div class="d-flex align-items-center mx-3 mt-5 mb-4">
                        <form class="w-100 text-end" id="form_login">
                            <div class="col-md-8 col-lg-8 col-xl-7 mx-auto">
                                <div class="mb-4 input-group">
                                    <label class="input-group-text" for="id_user"><i
                                            class="bi bi-person-badge-fill fs-5"></i></label>
                                    <input type="text" id="id_user" name="id_user" class="form-control" />
                                </div>
                                <div class="mb-4 input-group">
                                    <label class="input-group-text" for="id_password"><i
                                            class="bi bi-key-fill fs-5"></i></label>
                                    <input type="password" id="id_password" name="id_password" class="form-control" />
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-lg w-100" id="btnLogin" type="submit"><i
                                            class="bi bi-person-lock"></i> Ingresar</button>
                                </div>
                            </div>
                            <p class="small text-center mt-2"><a class="text-white" href="#!">¿Olvidó su contraseña?</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="position-absolute bottom-0 w-100 bg-dark text-light rounded rounded-3">
        <p class="fst-normal my-3 mx-3">SISDEC &copy; 2024 - <?php echo date('Y'); ?>
            | Contacto: <a href="mailto:sisdec.ancash@gmail.com" class="text-light">sisdec.ancash@gmail.com</a></p>
    </footer>

</body>
<script src="public/assets/js/resourse/jquery.js"></script>
<script src="public/assets/js/resourse/bootstrap.bundle.min.js"></script>
<script src="public/assets/js/resourse/sweetalert2.all.min.js"></script>
<script>
let index_alert = Swal.mixin({
    position: "center",
    showConfirmButton: true,
    cancelButtonText: 'Cancelar',
    customClass: {
        confirmButton: 'btn btn-primary mx-auto',
        cancelButton: 'btn btn-danger mx-auto'
    },
    buttonsStyling: false,
    didOpen: (Swal) => {
        Swal.style.boxShadow = "0 14px 8px rgba(0, 0, 0, 0.6)";
    }
});

let audio_error = new Audio('/public/assets/media/audios/error.mp3');

$(document).ready(function() {

    $("#form_login").submit(function(event) {
        event.preventDefault();
        let usuario = $("#id_user").val().trim();
        let password = $("#id_password").val().trim();

        if (usuario === "" || password === "") {
            index_alert.fire({
                icon: 'error',
                title: 'Campos Vacíos',
                text: 'Por favor, ingrese su usuario y contraseña.'
            });
            audio_error.play();
            return;
        };
        $.ajax({
            url: 'database/loginCTR.php',
            type: 'POST',
            data: {
                accion: 'login',
                usuario: usuario,
                password: password
            },
            dataType: 'json',
            beforeSend: function() {},
            success: function(data) {
                if (data.valor === '0') {
                    index_alert.fire({
                        icon: 'error',
                        title: data.text
                    });
                    audio_error.play();
                };
                if (data.valor === '1') {
                    window.location.href = 'main.php';
                };
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                audio_error.play();
            }

        });
    });
});
</script>