<?php
include_once '../lib/helpers.php';
?>
<!DOCTYPE html>

<html lang="ES">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shorcut icon" href="images/Gers.ico">
    <title>Gers S.A.S</title>
    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress --
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">-->
    <!-- jQuery custom content scroller -->
    <link href="vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet" />
    <!--datatables-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <!-- Custom Theme Style -->
    <link href="build/css/custom.css" rel="stylesheet">
    <link href="build/css/Mantenimientocss.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
</head>

<body>
    <!-- top navigation -->
    <div class="top_nav">
        <div class="nav_menu">
            <nav class="nav navbar-nav">
                <ul class=" navbar-right">
                    <li class="nav-item dropdown open " style="padding-left: 15px;">
                        <a>
                            <a class="text-white" href="./login.php">Iniciar sesion</a>
                        </a>
                        <div class="dropdown-menu dropdown-usermenu pull-right" style="color:white"
                            aria-labelledby="navbarDropdown">
                            <a class="dropdown-item"
                                href="indexUsu.php?modulo=Access&controlador=Access&funcion=logOut"><i
                                    class="fa fa-sign-out pull-right"></i>Cerrar Sesion</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class='right_col' role='main' style='background-image:url(images/electrica.jpg)'>
        <div class='clearfix'></div>
        <div class='row'>
            <div class='col-md-12 col-sm-12'>
                <div class=''>
                    <div class="fondo">
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="120px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="120px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="120px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="90px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="100px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="60px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="130px">
                        </div>
                        <div class='logo-animacion'>
                            <img src='images/Logogersblanco.png' width="80px">
                        </div>
                    </div>
                    <br>
                    <br>
                    <div style='margin-left:25%;' id="vacantes"  class="col-md-6 justify-content-start">

                    </div>
                    <input type="hidden" id="vac" name="" data-url="<?php echo geturl("Access","Access","vacantes",false,"ajax"); ?>">
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- /footer content -->
    </div>
    </div>
    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/jquery/dist/global.js"></script>
    <script src="vendors/jquery/dist/moment.min.js"></script>
    <!--sweet-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="build/js/custom.min.js"></script>
    <!--datatables-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <!-- NProgress 
<script src="vendors/nprogress/nprogress.js"></script>-->
    <!-- Custom Theme Scripts -->
    <!-----
<script src="build/js/validacioncontraseña.js"></script>
<script src="build/js/ValidacionFormacion.js"></script>
<script src="build/js/ValidacionExperienciaLaboral.js"></script>-->
    <div class="modal" id="modalUsu" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" class="modal-dialog modal-dialog-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo">Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="padding:5px;" id="contenedor">
                </div>
            </div>
        </div>
    </div>
    <div id="modalAdminLarge" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>
</body>
<footer>
    <script src="build/js/particles.min.js"></script>
    <script src="build/js/app.js"></script>
</footer>
<script>
 function load() {
        var url = $("#vac").attr("data-url");
        $.ajax({
            url: url,
            type: "POST",
            success: function(datos) {
            $("#vacantes").html(datos);
            }
        });
        
    
    }
    window.onload = load;
</script>
</html>
<?php
session_destroy();
?>