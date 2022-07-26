<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once '../model/Access/AccessModel.php';

class AccessController
{
    //Visualizar la vista de login
    public function getLogin()
    {
        include_once '../view/Registro/registrovista.php';
    }
    //Regitrar usuarios en la base de datos
    public function getInsert()
    {
        $obj = new AccessModel();
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
       
        extract($_POST);
        $correo=$_POST['usu_correo']; 
        $hash = password_hash($_POST['usu_contraseña'], PASSWORD_BCRYPT);
        $sql2="SELECT usu_correo FROM tbl_usuario WHERE  usu_correo='".$correo."'";
        $correobd=$obj->consult($sql2);
        $row=$correobd->num_rows;
        if($row==0){
        $token = str_shuffle("0123456789" . uniqid());
        $usu_id = $obj->autoIncrement("tbl_usuario", "usu_id");
        $sql = "INSERT INTO tbl_usuario VALUES('$usu_id ', '$usu_nombre ','$usu_apellido ',
        '$usu_correo ', '$usu_telefono ','$usu_pais_residencia','$usu_residencia','$usu_direccion',
        '$usu_tipo_documento ', '$usu_documento ','$hash','$usu_termino',2,NULL,NULL,NULL,NULL,NULL,NULL)";
        $execution = $obj->insert($sql);
        ////// MANDAR CORREO CUANDO YA SE VALIDO QUE ES CORREO NO SE HAYA UTILIZADO YA EN EL SISTEMA
        //VALIDAR EL CORREO 
    $mail = new PHPMailer();                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                // Enable verbose debug output

        $mail->CharSet = 'UTF-8';

        $mail->isSMTP();         
                                    // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'crm@gers.com.co';                 // SMTP username
        $mail->Password = 'GersAdmin123.';                           // SMTP password
        $mail->SMTPSecure = 'TSL';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port =587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('crm@gers.com.co', 'Soporte Gers');
        $mail->addAddress($correo);     // Add a recipient
        //$mail->addAddress("crm@gers.com.co");                    // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
       // $mail->addBCC('');
        // $mail->addCC('bcc@example.com');

        //Attachments
        $mail->addAttachment('images/G.png');     // Add attachments
        $mail->addAttachment('Gestion Humana - Gers');    // Optional name

        //Content
        $mail->isHTML(true);// Set email format to HTML
        $mail->Subject = 'Validación de correo electronico';
        $mail->Body    = "Ha iniciado el proceso de registro en nuestro sistema de <b>RECURSOS HUMANOS</b>, copie este codigo <b>$token</b> e ingreselo en el sistema o dele clic al siguiente enlace <strong>http://localhost/RecursosHumanos/web/ajax.php?modulo=Access&controlador=Access&funcion=viewcorreo</strong>, para poder registrarte<br><b>Si no solicito ningun codigo de registro de su correo electronico en el sistema GERS S.A.S, ignore este mensaje.</b>";
        //$mail->AltBody = '';
        $mail->send();
        $sql = "UPDATE tbl_usuario SET usu_validarcorreo='$token' WHERE usu_correo='$correo' ";
        $execution = $obj->update($sql);
        if ($execution) {
            echo "<script>alert('Hemos enviado a su correo electronico un codigo de verificación(Revise su carpeta de spam).');</script>";           
           redirect(getUrl("Access", "Access", "viewcorreo",false, "ajax"));
          
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
        //FIN DE VALIDAR CORREO
        ////////////////////////////////////FIN DE LA VALIDACION DE CORREO
        } else {
            echo "<script>alert('Este correo electronico ya se encuentra registrado en el sistema');</script>";
            redirect('Login.php');  
        }  
    }


    //FUNCION APRA VALIDAR CORREO ELECTRONICO
    public function validarcorreo(){
        $obj = new AccessModel();
        $usu_correo = $_POST['usu_correo'];
        $usu_token = $_POST['usu_token'];

        $sql = "SELECT usu_validarcorreo,usu_correo FROM `tbl_usuario` WHERE usu_validarcorreo='$usu_token' AND usu_correo='$usu_correo'";
        $consultToken = $obj->consult($sql);

        if (mysqli_num_rows($consultToken) > 0) {
            $sql = "UPDATE tbl_usuario SET usu_validarcorreo=NULL WHERE usu_correo='".$usu_correo."' ";

            $execution = $obj->update($sql);

            if ($execution) {
                //aqui va una alerta para mostrar que ya se cambio 
                echo '<script>alert("El correo electronico se ha validado correctamente!");</script>';
                redirect('login.php');   
            }
        }else {
            //aqui una alerta por si no se cambio.
            echo '<script>alert("Ha ingresado datos incorrectos por favor intentelo nuevamente!");</script>';
            redirect(getUrl("Access", "Access", "viewcorreo",false, "ajax"));
        }
    }

    //FUNCION DE LA VISTA PARA VALIDAR EL CODIGO
    public function viewcorreo(){
        include_once '../view/login/validarcorreo.php'; 
    }

    //acceso en el login
    public function login()
    {
        $obj = new AccessModel();

        $usu_correo = $_POST['usu_correo'];
        $usu_contraseña = $_POST['usu_contraseña'];


        //pregunta si me llega una consulta y si el campo de valido es null, para que valide su cuenta
       
        $selectedPass = "SELECT usu_contraseña FROM tbl_usuario WHERE usu_correo='$usu_correo'";
        $passord_V = $obj->consult($selectedPass);
        if (mysqli_num_rows($passord_V)>0) {
            $correovalidado = "SELECT usu_correo FROM tbl_usuario WHERE usu_correo='$usu_correo' AND usu_validarcorreo IS NULL";
            $passord_V2 = $obj->consult($correovalidado);
             //pregunta si me llega mas de una consulta
        if (mysqli_num_rows($passord_V2) > 0) {
           foreach ($passord_V as $pass) {
                $hash_verify_db = $pass['usu_contraseña'];
            }
        //si concuerda la contraseña seleccioneme ese usuario y redireccionalo a ADMIN O USUARIO
            if (password_verify($usu_contraseña, $hash_verify_db)) {
                
                $sqlUser = "SELECT usu_id, usu_nombre, usu_apellido, usu_correo, usu_contraseña, rol_id FROM tbl_usuario WHERE usu_correo='" . $usu_correo . "' AND usu_contraseña='" . $hash_verify_db . "' AND rol_id=1";
                $usuario = $obj->consult($sqlUser);
                if (mysqli_num_rows($usuario) > 0) {
                    foreach ($usuario as $user) {

                        $_SESSION['nameUser'] = $user['usu_nombre'];
                        $_SESSION['surnameUser'] = $user['usu_apellido'];
                        $_SESSION['rolId']=$user['rol_id'];
                        $_SESSION['idUser'] = $user['usu_id'];
                        $_SESSION['auth'] = "ok";
                    }
                    if ($_SESSION['idUser'] > 0 ){
                        $sql = "UPDATE tbl_vacante SET id_estadovacante = 2 WHERE DATE_FORMAT(CURDATE(),'%y%m%d') >= vac_fecha  AND id_estadovacante=1";
                        $resultado = $obj->update($sql);
                }
                    redirect("indexAdmin.php");
                } 
                $sqlUser2 = "SELECT usu_id, usu_nombre, usu_apellido, usu_correo, usu_contraseña, rol_id FROM tbl_usuario WHERE usu_correo='" . $usu_correo . "' AND usu_contraseña='" . $hash_verify_db . "' AND rol_id=2";
                $usuario2 = $obj->consult($sqlUser2);
                if (mysqli_num_rows($usuario2) > 0) {
                    foreach ($usuario2 as $user) {

                        $_SESSION['nameUser'] = $user['usu_nombre'];
                        $_SESSION['surnameUser'] = $user['usu_apellido'];
                        $_SESSION['rolId']=$user['rol_id'];
                        $_SESSION['idUser'] = $user['usu_id'];
                        $_SESSION['auth'] = "ok";
                    }
                    if ($_SESSION['idUser'] > 0 ){
                        $sql = "UPDATE tbl_vacante SET id_estadovacante = 2 WHERE DATE_FORMAT(CURDATE(),'%y%m%d') >=  vac_fecha  AND id_estadovacante=1";
                        $resultado = $obj->update($sql);
                    }
                  
                    redirect("indexUsu.php");
                } else {
                    echo "<script>alert('El correo o contraseña no coinciden Vuelva a intentarlo'); </script>";
                    redirect('login.php');
                    
                }
            }else {
                //alerta de que no coincide la contraseña
                echo "<script>alert('El correo o contraseña no coinciden Vuelva a intentarlo'); </script>";
                redirect('login.php');
            }
            

        } else {
            echo "<script>alert('Aún no ha activado su cuenta, por favor ingrese a su correo electronico para la validación'); </script>";
            redirect('login.php');
        }
    
    }else {
        echo "<script>alert('El correo o contraseña no coinciden Vuelva a intentarlo'); </script>";
        redirect('login.php');
    }

    }


// CIERRE DE SESION
    public function logOut()
    {
        session_destroy();
        redirect('login.php');
    }

    public function timeline(){
      $obj = new AccessModel();
      /// TIME LINE
      //DOCUMENTOS DE SOPORTE DEL USUARIO
      $sql="SELECT usu_hojadevida,usu_matricula,usu_fechamatricula,usu_fechahojadevida FROM tbl_usuario WHERE usu_id='".$_SESSION['idUser']."'";
      $usuario=$obj->consult($sql);
      ///ESTUDIOS DE LA PERSONA
      $sql="SELECT * FROM tbl_formacion WHERE usu_id='".$_SESSION['idUser']."'";
      $formacion=$obj->consult($sql);
     //EXPERIENCIA DE LA PERSONA
     $sql="SELECT * FROM tbl_historial_de_trabajo WHERE usu_id='".$_SESSION['idUser']."'";
     $historial=$obj->consult($sql);

      echo "<div class='card'>
            <div  class='text-center titles card-header'>
           MIS EXPERIENCIAS PROFESIONALES
            </div>";?>
<?php foreach ($historial as $hist) {?>
<div class='card-body'>
    <blockquote class='blockquote mb-0'>

        <p class="time"><b><?php echo $hist['hist_cargo']?></b></p>
        <p class="time"><b>EMPRESA:</b><?php echo $hist['hist_cargo']?></p>
        <p class="time"><b>DESCRIPCIÓN DE ACTIVIDADES:</b><?php echo $hist['hist_cargo']?></p>
        <p class="time"><b>CIUDAD:</b><?php echo $hist['hist_cargo']?></p>
        <p class="time"><b>PAIS:</b><?php echo $hist['hist_cargo']?></p>
        <p class="time"><b>CERTIFICADO:</b> <a id='consultarhistorial' name='id_hist' type='button'
                data-url="<?php echo getUrl("Usuario","Ofertas","consultarhistorial",array("id_hist" => $hist['id_hist']),"ajax")?>"
                class='btn btn-secondary radios'><i class='fa fa-file'></i></a></p>
        <footer class='blockquote-footer'>Registrado:<cite title='Source Title'><?php echo $hist['hist_time']?></cite>
        </footer>
    </blockquote>
</div>

<?php   } ?>
<a href="<?php echo getUrl2("Usuario","Ofertas","experiencia");?>" style='' class="btn btn-info">Editar</a></div><br>
<div class='card'>
    <div class='titles text-center card-header '>
        MIS ESTUDIOS
    </div><?php
           foreach ($formacion as $form) {              
                           echo"<div class='card-body'>
                            <blockquote class='blockquote mb-0'>
                            <footer style='text-align:right;'class='blockquote-footer'><b>Fecha graduación: </b><cite title='Source Title'>".$form["form_fecha_fin"]."</cite></footer>
                            <p class='time'><b>".$form['form_tituloname']."</b></p>
                            <p class='time'><b>NIVEL DE EDUCACIÓN:</b> ".$form["form_nivel_de_educacion"]."</p>
                            <p class='time'><b>NOMBRE DE LA INSTITUCIÓN:</b> ".$form["form_nombre"]."</p>
                            <p class='time'><b>CONOCIMIENTOS O HABILIDADES:</b> ".$form["form_conocimientos"]."</p>
                            <p class='time'><b>TITULO:</b> <a id='consultarcertificado' type='button'
                                        data-url='".getUrl("Usuario","Ofertas","consultarcertificado",array("id_formacion" => $form['id_formacion']),"ajax")."'
                                    class='btn btn-primary radios'><i class='fa fa-file'></i></a></p>
                            <footer class='blockquote-footer'>Registrado:<cite title='Source Title'>".$form['form_time']."</cite></footer>
                            </blockquote>
                        </div>";
                        
        }
        echo "<a href='". getUrl2("Usuario","Ofertas","consultusu")."'  style='' class='btn btn-info'>Editar</a></div><br>";
        echo "<div class='card'>
            <div  class='text-center titles card-header'>
               DOCUMENTOS DE SOPORTE
            </div>";
        ?>
    <?php
            foreach ($usuario as $usu) { 
                ?>
    <div class='card-body'>
        <blockquote class='blockquote mb-0'>
            <p class="time"><b>HOJA DE VIDA</b></p>
            <p class="time"><a type="button" title="Visor hoja de vida" target="_black"
                    href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/RecursosHumanos'.$usu['usu_hojadevida']?>"
                    class="radios modalhojadevida btn btn-primary"><i class="fa fa-file"></i></a></p>
            <footer class='blockquote-footer'>Ultima actualización: <cite
                    title='Source Title'><?php echo $usu['usu_fechahojadevida']?></cite></footer>
        </blockquote>
    </div>
    <div class='card-body'>
        <blockquote class='blockquote mb-0'>
            <p class="time"><b>TARJETA PROFESIONAL</b></p>
            <p class="time"><a title="Visor matricula profesional" type="button" target="_black"
                    href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/RecursosHumanos'.$usu['usu_matricula']?>"
                    class="radios btn btn-primary"><i class="fa fa-file"></i></a></p>
            <footer class='blockquote-footer'>Ultima actualización: <cite
                    title='Source Title'><?php echo $usu['usu_fechamatricula']?></cite></footer>
        </blockquote>
    </div>
    <a href='<?php echo getUrl2("Usuario","Ofertas","documento");?>' style='' class='btn btn-info'>Editar</a>
</div>
<?php  }?>
<?php  
    }






// vacantes del exteriro del sistema
public function vacantes(){
    $obj= new AccessModel();
    $sql="SELECT * FROM tbl_vacante WHERE id_estadovacante='1'";
    $aplicaciones=$obj->consult($sql);
    ?>
    <!-- PARA LA VISTA DE AFUERA DE LOS USUARUIOS -->
    <div style="padding-top:10px;" class="x_title">
        <h5 style="font-weight:bolder;" class="text-center">OFERTAS LABORALES</h5>
    </div>
    <div style="padding-top:10px;" class="x_content justify-content-start col-md-12">
        <?php
    foreach ($aplicaciones as $vac) {
    echo"<div  style='' class='card col-md-12'>
      <div class='card-body'>
        <h5 class='card-title'><b>".$vac['vac_nombre'] ."</b></h5>";
    echo "<p class='card-text'></p>
    </div>
      <ul class=''>
        <li class=''><b>Fecha de contratación: </b>". $vac['vac_fecha']."</li>
        <li class=''><b>Años de experiencia: </b>". $vac['vac_años_xp']."</li>
        <li class=''><b>Jornada laboral:</b> ".$vac['vac_jornada_laboral']."</li>
        <li class=''><b>Tipo contrato:</b> ".$vac['vac_tipo_contrato']."</li>
        <li class=''><b>Salarío:</b> ".$vac['vac_salario']."</li>
        <li class=''><b>Nivel de educación:</b> ".$vac['vac_educacion']."</li>
    </ul>
    <div style='padding-top:10px;' class='card-body'>
    <a id='modaldetallevacante' class='radios btn btn-warning' value'' data-url='" . getUrl("Usuario", "Ofertas", "modaldetalle", array("id_vacante" => $vac['id_vacante']), "ajax") . "'>Ver detalle</a>
        <a class='radios btn btn-primary'  name='id_vacante' href='./login.php'>Aplicar</a>
    </div>
    </div>";
    }
    ?>
    </div>
<?php 
} 
}?>