<?php
    use PHPMailer\PHPMailer\PHPMailer;
	require_once("connecta_db.php");
    $selectAll = "SELECT * FROM users WHERE username=:username OR mail=:username";
    $mostraUser=$db->prepare($selectAll);

    $updUser = "UPDATE users SET lastSignIn = :fecha WHERE username=:username OR mail=:username";
    $update = $db->prepare($updUser);

    $updUser = "UPDATE users SET  activationCode=null, activationDate=:fecha, active=1 WHERE mail=:mail";
    $ActivarUser = $db->prepare($updUser);
   
    function setDate(){
        $timezone = date_default_timezone_get();
        date_default_timezone_set($timezone);
        $date = date('Y-m-d h:i:s', time());
        return $date;
    }
    function registrarBD(){
        include("connecta_db.php");
        $db->beginTransaction();
        $sql = "INSERT INTO users(mail,username,passHash,userFirstName,userLastName,creationDate,active,activationCode) 
        VALUES(:mail,:username,:passHash,:userFirstName,:userLastName,:creationDate,:active,:codeActiu)";
        $insert=$db->prepare($sql);
        return $insert;
    }

    function error(){
        echo"<script type='text/javascript'>
        $(document).ready(function() {
            swal({
                title: 'Error',
                text: 'Something get wrong!',
                icon: 'error',
                button: 'Ok'
            });
        });
        </script>";
    }
    function success(){
        echo"<script type='text/javascript'>
        $(document).ready(function() {
            swal({
                title: 'User Created!',
                text: 'Register success!',
                icon: 'success',
                button: 'Ok',
                timer: 2000
            });
        });
        </script>";
    }
    
    function enviarMail($correoCli,$username,$codeActivacio){
        require './vendor/autoload.php';    
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        
        $mail->Username = 'adria.priegog@educem.net';
        $mail->Password = '0707D@2122';

        //Dades del correu electrònic
        $mail->SetFrom('no-reply@cinetics.com','no-reply');
        $mail->Subject = 'Confirm your mail account';
        
        $mail->MsgHTML(messageMail($username,$correoCli,$codeActivacio));        
        //Destinatari
        $mail->AddAddress($correoCli, $username);

        //Enviament
        $result = $mail->Send();
        if(!$result){
            echo 'Error: ' . $mail->ErrorInfo;
        }else if($result!=NULL){
            echo "Correu enviat";
        }
    }

    function messageMail($username,$mail,$code){
        $message  = "<html><body>";
        $message .= "<table width='100%' bgcolor='#E0E0E0' cellpadding='0' cellspacing='0' border='0'>";
        $message .= "<tr><td>";
        $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
        $message .= "<thead>
            <tr height='80'>
            <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #BDBDBD; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;' >Verify Email</th>
            </tr>
            </thead>";
        $message .= "<tbody>
            <tr>
            <td colspan='4' style='padding:15px;'>
            <p style='font-size:20px;'>Hi ".$username."</p>
            <hr />
            <p style='font-size:25px;'>Now you can verify your Email</p>
            <img src='../img/favicon.ico' alt='ImatgeCinetics' title='ImatgeCinetics' style='height:auto; width:100%; max-width:100%;' />
            <a href='http://localhost/Web/mailCheckAccount.php?code=".$code."&mail=".$mail."' style='font-size:15px;' align='center' 'font-family:Verdana, Geneva, sans-serif;'>Active your account Now!</a>
            </td>
            </tr>
            </tbody>";
        $message .= "</table>";
        $message .= "</td></tr>";
        $message .= "</table>";
        $message .= "</body></html>";
        return $message;
    }
?>