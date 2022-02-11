<!DOCTYPE html>
<html>
<head>
	<title>Cinetics</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">
    <link rel="icon" type="image/ico" href="./img/favicon.ico"/>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<?php
	session_start();

	if(isset($_SESSION['user_id'])){
		header('Location: home.php');
		exit;
	}
	include("./Llibreries/querysSQL.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['password'])){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$mostraUser->execute(array(':username'=>$username));      
			foreach ($mostraUser as $fila) {
				$passSelect=$fila['passHash'];
				$idUser=$fila['iduser'];
				$active=$fila['active'];
				$nombreUsuario=$fila['username'];
			}
			if(empty($active))$active=null; 
			if($active==1){
				if (password_verify($password,$passSelect)) {
					$date=setDate();
					$update->execute(array(':username'=>$username,':fecha'=>$date));
					if($update){
						session_start();    
						$_SESSION['user_id'] = $idUser;
						$_SESSION['username'] = $nombreUsuario;
						header('Location: ./home.php');
						exit;    
					}else{
						print_r( $db->errorinfo());
					}
				} 
			}    
			else {
				error();
			}	
		}
		else if(isset($_POST['username'])){
			$username = $_POST['username'];
			$mostraUserMail->execute(array(':username'=>$username));
			$codeReset=hash('sha256',random_int(0,10000));
			foreach($mostraUserMail as $fila){
				$nombreUsuario=$fila['username'];
				$mailUsuario=$fila['mail'];
			}
			$fecha = new DateTime();
			$fecha -> modify('+30 minute');
			$fecha=$fecha->format('d-m-Y H:i:s');
			$SetResCode->execute(array(':mail'=>$mailUsuario,':codeReset'=>$codeReset,':fecha'=>$fecha));
			enviarPass($mailUsuario,$nombreUsuario,$codeReset);
		}
		else {
			error();
		}
	}
?>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Sign In</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
					<span><i class="fab fa-twitter-square"></i></span>
				</div>
			</div>
			<div class="card-body">
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" id="username" name="username" placeholder="Username/Mail" autocomplete="off" autofocus required>
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="Password" id="password" name="password" autocomplete="off" required>
					</div>
					<div class="form-group">
						<input type="submit" value="Login" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					Don't have an account?<a href="./register.php">Sign Up</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="#" data-toggle="modal" data-target="#ModalForgot">Forgot Password?</a>
					<!-- Modal -->
					<div class="modal fade" id="ModalForgot" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">Insert your username/email</h5>
									</div>
									<div class="modal-body">
										<input type="text" class="form-control" id="mailf" name="username" autocomplete="off" required>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<input type="submit" value="Send" class="btn float-right login_btn">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>