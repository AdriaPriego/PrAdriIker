<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
	session_start();

	if(isset($_SESSION['user_id'])){
		header('Location: home.php');
		exit;
	}
	include("./Llibreries/querysSQL.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$error=false;
		if(isset($_POST['user']) && isset($_POST['mail'])
        && isset($_POST['pass']) && isset($_POST['passVerify'])){
			if($_POST['pass']==$_POST['passVerify'])
			{
				require_once("./Llibreries/connecta_db.php");	
				$userPost=filter_input(INPUT_POST,'user');
				$mailPost=filter_input(INPUT_POST,'mail');
				$fnamePost=filter_input(INPUT_POST,'fname');
				$lnamePost=filter_input(INPUT_POST,'lname');
				$passPost=filter_input(INPUT_POST,'pass');	
				$passVPost=filter_input(INPUT_POST,'passVerify');
				$date = setDate();
				$passHash=password_hash($passPost,PASSWORD_DEFAULT);
				$insert=registrarBD();
				$insert->execute(array(':mail'=>$mailPost,':username'=>$userPost,':passHash'=>$passHash,':userFirstName'=>$fnamePost,':userLastName'=>$lnamePost,':creationDate'=>$date ,':active'=>1));        
				///COMPROBA QUE NO INSERTI LES MATEIXES DADES UN ALTRE COP
				if(($insert->rowCount())==0){
					//Anulem transacció
					$db->rollback();
					$error=true;
				}else{
					//Ha anat bé
					$db->commit();
				}
			}
			else{
				$error=true;
			} 
			
		}
		else {
			$error=true;
		}

	} 
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cinetics</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">   
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">    
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">
    <link rel="icon" type="image/ico" href="./img/favicon.ico"/>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
            <div class="card-header">
                <h3>Complete the fields</h3>
				<?php
					if(isset($error)){
						if($error){
							error();
						}
						else {
							header('Location: ./index.php');
							exit;
						}	
					}
				?>
            </div>
			<div class="card-body">
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="text" class="form-control" id="user" name="user" placeholder="Username" autocomplete="off" value="<?php if(isset($_POST["user"])) echo $_POST["user"];?>" required>
					</div>
                    <div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="email" class="form-control" id="mail" name="mail" placeholder="Mail" autocomplete="off" value="<?php if(isset($_POST["mail"])) echo $_POST["mail"];?>" required>
					</div>
                    <div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="<?php if(isset($_POST["fname"])) echo $_POST["fname"];?>" autocomplete="off">
					</div>
                    <div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="<?php if(isset($_POST["lname"])) echo $_POST["lname"];?>" autocomplete="off">
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="password" class="form-control" placeholder="Password" id="pass" name="pass" autocomplete="off" required>
					</div>
                    <div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"></span>
						</div>
						<input type="password" class="form-control" placeholder="Verify Password" id="passVerify" name="passVerify" autocomplete="off"  required>
					</div>
					<div class="form-group">
						<input type="submit" value="Sing up" class="btn float-right login_btn">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>