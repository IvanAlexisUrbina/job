<?php
    include_once '../lib/helpersUsu.php';
    include_once '../view/partials/header.php';
    echo "<body class='nav-md'>";
        echo "<div class='container body'>";
            echo "<div class='main_container'>";
              include_once '../view/partials/sidebarUsu.php';
                include_once '../view/partials/navbarUsu.php';
                $fondo="background-image:url(images/electrica.jpg)";
                echo "<div class='right_col' role='main' style='background:white;'>";
                    echo "<div class='clearfix'></div>";
                    
                    echo "<div class='row'>";
                        echo "<div class='col-md-12 col-sm-12'>";
                        
                        echo "<div class=''>";
                     include_once '../view/partials/fondoanimado.php';

                            if (isset($_GET['modulo'])) {
                                resolve();
                            }else{
                                include_once '../view/partials/content2.php';
                            }
                        
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        include_once '../view/partials/footer.php';
        include_once '../view/partials/modalUsu.php';
        include_once '../view/partials/modalLargeAdmin.php';
    echo "</body>";
    echo "</html>";
                    
?>