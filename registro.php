<?php session_start(); ?>
<?php $pag='Registro'; ?>
<?php require 'includes/encabezadoLog.php'; ?>
<?php  $error= false;  
$msj=array();
$msj[1]['text'] = "E-mail ya ha sido registrado!";
$msj[1]['type'] = "error";
$msj[2]['text'] = "Ingresá una fecha de nacimiento válida";
$msj[2]['type'] = "error";
$msj[3]['text'] = "Ingresa una foto válida!";
$msj[3]['type'] = "error";
$msj[4]['text'] = "Las claves no coinciden";
$msj[4]['type'] = "error";
?>

<?php if (!empty($_POST)){
	if (isset($_POST['nombre']) && isset($_POST['apellido'])
		&& isset($_POST['email'])&& isset($_POST['clave']) 
		&& isset($_POST['claveRep'])
		&& isset($_POST['tel']) && isset($_POST['fecha'])){
			$con = conexion();
		
			$nombre = mysqli_real_escape_string($con,$_POST['nombre']);
			$apellido = mysqli_real_escape_string($con,$_POST['apellido']);
			$email = mysqli_real_escape_string($con,$_POST['email']);
			$clave = mysqli_real_escape_string($con,$_POST['clave']);
			$claveRep = mysqli_real_escape_string($con,$_POST['claveRep']);
			$tel = mysqli_real_escape_string($con,$_POST['tel']);
			$nacimiento = mysqli_real_escape_string($con,$_POST['fecha']);

			$query = "SELECT * FROM usuario WHERE mail='$email';";
			$result = fetch($query,true);
			if ($clave!=$claveRep) {
				$error = 4;
			} else {
				if (empty($result)) {
					
    				/*$fechaValida = date("d-m-").(date("Y")-7);*/
    				$fechaValida = (date("Y")-7).date("-m-d");
    				/*$feV=explode('-', $fechaValida);
    				$añoValido=$feV[0];
    				
    				$nac=explode('/', $nacimiento);
    				$añoNac=$nac[0];*/
					
    				if ($nacimiento <= $fechaValida) {
    				
    					$query = "INSERT INTO `usuario` (`nombre`, `apellido`, `mail`, `clave`, `telefono`, `nacimiento`, `puntaje`) VALUES ( '$nombre', '$apellido', '$email', '$clave', '$tel', '$nacimiento', '1');";

						$res = accion($query, true);
						
						if($res || $res==0) {
							$lastInsertId = $res;
						}else { die();}

						if (count($_FILES)) {
							$fotoTmp = $_FILES['foto']['tmp_name'];
							$foto = $_FILES['foto']['name'];
							$tipo = $_FILES['foto']['type']; //image/png
							$tipoEx = explode('/', $tipo);
							$tipoArchivo = $tipoEx[0];
							$ext = $tipoEx[1];
							$fotoFull="uploads/".time()."$foto";
							move_uploaded_file($fotoTmp, $fotoFull);
							
							if ($tipoArchivo!='image') {
								
								$error = 3;

							} else {

								$query = "UPDATE `usuario` SET `foto`='$fotoFull' WHERE `idUsuario`='$lastInsertId';
	";
								accion($query);
								header("location:gauchada.php?id=$lastInsertId");
							}
						} 
					
						$_SESSION['logueado'] = true;
						$_SESSION['idUsuario'] = $lastInsertId;
						$_SESSION['mail'] = $email;
						$_SESSION['telefono']=$tel;
						$_SESSION['nombre']=$nombre;
						$_SESSION['admin'] = $res['nivelUsuario'];
					
						header("location:index.php");

					} else { $error = 2;}
				} else {
				$error = 1;
			}
		}
	} 
} ?>

<div class="menu">
	<h3 class="titulo">Registrarse</h3>
	<div class="<?php echo $msj[$error]['type']; ?>">
	<?php 
		if ($error) {
			echo $msj[$error]['text'];
		}
	?>
	</div>
	<?php if (isset($_POST['nombre'])) {

		 $nombre=$_POST['nombre'];
	} ?>
	<?php if (isset($_POST['apellido'])) {
		
		 $apellido=$_POST['apellido'];
	} ?>
	<?php if (isset($_POST['email'])) {
		
		 $email=$_POST['email'];
	} ?>
	<?php if (isset($_POST['tel'])) {
		
		 $telefono=$_POST['tel'];
	} ?>

	<?php if (isset($_POST['fecha'])) {
		$fecha = $_POST['fecha'];
	} ?>

	<form method="POST" action="registro.php" enctype="multipart/form-data" >
		<label>Nombre:
		<input type="text" name="nombre" placeholder="Ingrese su nombre" value="<?php echo "$nombre"; ?>" required>
		</label><br><br>
		<label>Apellido:
		<input type="text" name="apellido" placeholder="Ingrese su apellido" value="<?php echo $apellido ?>"required></label><br><br>
		<label>E-mail:
		<input type="email" name="email" placeholder="Ingrese su e-mail" value="<?php if($error!=1){ echo $email; }?>" required></label><br><br>
		<label>Foto (opcional)</label>					
		<input name="foto" type="file" accept="image/*"><br><br>  
		<label>Contraseña:
		<input type="password" name="clave" placeholder="Ingrese una contraseña" required></label><br><br>
		<label>Repetir contraseña:
		<input type="password" name="claveRep" placeholder="Repita la contraseña" required></label><br><br>
		<label>Teléfono:
		<input type="text" name="tel" placeholder="Ingrese su número" value="<?php echo $telefono ?>" maxlength="10" required></label><br><br>
		<label>Fecha de Nacimiento:
		<input type="date" name="fecha" placeholder="DD-MM-AAAA" value="<?php if ($_POST['clave']!=$_POST['claveRep']) { echo $fecha; } else { echo "dd-mm-aaaa"; } ?>" maxlength="10" required></label><br><br>
		<button id="boton">Registrarme</button>

	</form>
</div>
<?php require 'includes/footer.php'; ?>

<?php

     #_#_#
     /* *\
      |0|
 //*|||||||*\\
//   |-8-|   \\ 
     \*|*/
 ?>