<?php session_start();

include 'bd.php'; 

if ($_GET['op']=='mod') {
	
	#modificación de datos
	
	if (isset($_POST['idU']) && isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['tel']) && isset($_POST['fecha']) && isset($_POST['email'])) {
		
		$idU = $_POST['idU'];
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$tel = $_POST['tel'];
		$nacimiento = $_POST['fecha'];

		$email = $_POST['email'];
		$query = "SELECT * FROM usuario WHERE mail='$email';";
		$result = fetch($query,true);
		if ($result!=NULL) {
			if ($result['idUsuario']!=$idU) {
				header('location:perfil.php?op=mod&msj=6'); exit();
			}
		}
		/*if (!empty($_POST['email'])) {
			$email = $_POST['email'];
			$query = "SELECT * FROM usuario WHERE mail='$email';";
			$result = fetch($query,true);

			if ($result==NULL) {
				$email = $_POST['email'];
			} else if ($result!=NULL) { header('location:perfil.php?op=mod&msj=6'); exit();}
		} 
			if(empty($_POST['email'])){
			$queryU = "SELECT * FROM usuario WHERE idUsuario='$idU';";
			$resultado = fetch($queryU,true);
			$email = $resultado['mail'];
		}*/
		

    	$fechaValida = date("d-m-").(date("Y")-7);
    	$feV=explode('-', $fechaValida);
    	$añoValido=$feV[2];
    	$nac=explode('-', $nacimiento);
    	$añoNac=$nac[0];

    	
	   	if ($añoNac <= $añoValido)  {
	    		$queryM = "UPDATE `usuario` SET `nombre`='$nombre', `apellido`='$apellido', `telefono`='$tel', `mail`='$email', `nacimiento`='$nacimiento' 
	    				WHERE `idUsuario`='$idU';";
	    		accion($queryM);
	    		header('location:perfil.php?msj=3');
		} else { header('location:perfil.php?op=mod&msj=2');  }
    	
	} 
} else if ($_GET['op']=='foto') {

	#modificación de foto

	$idU = $_POST['idU'];

		
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
			header("location:perfil.php?op=foto&msj=4");
		} else {

			$query = "UPDATE `usuario` SET `foto`='$fotoFull' 
						WHERE `idUsuario`='$idU';";
			accion($query);
			header("location:perfil.php?msj=5");
		}
	} 
} else if ($_GET['op']=='pass') {
	
	#modificación de pass

	if (isset($_POST['clave']) && isset($_POST['claveRep'])) {
		$idU = $_POST['idU'];
		$clave = $_POST['clave'];
		$claveRep = $_POST['claveRep'];

		if ($clave!=$claveRep) {
				header('location:perfil.php?op=pass&msj=7');
		} else{
			$query="UPDATE `mydb`.`usuario` SET `clave`='$clave' WHERE `idUsuario`='$idU'";
			accion($query);
			header('location:perfil.php?msj=8');
		}
	}
}

?>