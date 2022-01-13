<?php
	require_once("connecta_db.php");
    $selectAll = "SELECT * FROM users WHERE username=:username OR mail=:username";
    $mostraUser=$db->prepare($selectAll);

    $updUser = "UPDATE users SET lastSignIn = :fecha WHERE username=:username OR mail=:username";
    $update = $db->prepare($updUser);
   
    function setDate(){
        $timezone = date_default_timezone_get();
        date_default_timezone_set($timezone);
        $date = date('Y-m-d h:i:s', time());
        return $date;
    }
    function registrarBD(){
        include("connecta_db.php");
        $db->beginTransaction();
        $sql = "INSERT INTO users(mail,username,passHash,userFirstName,userLastName,creationDate,active) 
        VALUES(:mail,:username,:passHash,:userFirstName,:userLastName,:creationDate,:active)";
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
                button: 'Ok',
                timer: 2000
            });
        });
        </script>";
    }

?>