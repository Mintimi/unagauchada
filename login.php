<?php session_start(); ?>
<?php $pag='LogIn'; ?>
<?php require 'includes/encabezadoLog.php'; ?>

<?php
$error=false;
if (!empty($_POST)) {
 	if ( !empty($_POST['email']) && !empty($_POST['pass']) ) {
	
	$email = $_POST['email'];

	$query="SELECT 
				idUsuario,
			    clave,
			    mail,
			    nombre,
			    apellido,
			    telefono,
			    nivelUsuario

			FROM
			    usuario
			WHERE
			    mail = '$email';";

	$result = fetch($query,true);
	
	$claveBd = $result['clave'];
	$clave = $_POST['pass'];

	if ( $claveBd == $clave) {
		
		$_SESSION['logueado'] = true;
		$_SESSION['idUsuario'] = $result['idUsuario'];
		$_SESSION['mail'] = $result['mail'];
		$_SESSION['telefono']=$result['telefono'];
		$_SESSION['nombre'] = $result['nombre'];
		$_SESSION['apellido'] = $result['apellido'];
		$_SESSION['admin'] = $result['nivelUsuario'];
		header("location:index.php");

	} else $error = "<h4 class='error'>Datos incorrectos</h4>";
		

	} else if ( !empty($_POST['email']) && empty($_POST['pass']) ){
		
		$error = "<h4 class='error'>Escribí tu clave</h4>";


	} else if ( empty($_POST['email']) && !empty($_POST['pass']) ){
		
		$error = "<h4 class='error'>Escribí tu email</h4>";

	} else if ( empty($_POST['email']) && empty($_POST['pass']) ){
		$error =  "<h4 class='error'>Rellená los dos campos requeridos</h4>";
	}
 }

?>
<div class="menu">
	<h3 class="titulo">Iniciar Sesión</h3>
	<?php 
		if ($error) {
			echo $error;
		}
	?>
	<form method="POST" action="login.php">
		<label>E-mail:
			<input type="email" name="email" placeholder="Ingrese su e-mail">
		</label><br><br>	
		<label>Contraseña:
			<input type="password" name="pass" placeholder="Ingrese su contraseña">
		</label><br><br>		
		<button>Entrar</button>
	</form>
</div>
<?php require 'includes/footer.php'; ?>