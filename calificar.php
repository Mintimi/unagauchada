<?php session_start(); ?>
<?php $pag='Calificar usuario'; ?>
<?php require 'includes/encabezadoLog.php'; ?>
<?php 
  $msj = array();
  $msj[1]['text'] = "Seleccione una calificación"; 
  $msj[1]['type'] = 'error';
  $error = false;
  
  #####################################################################
 
   if (!empty($_POST) || isset($_GET['idU'])){

	   if ($_POST['calificacion']!=0 && isset($_POST['comentario'])) {
			
			$idG = $_POST['idG'];
			$cal = $_POST['calificacion'];
			$com = $_POST['comentario'];
			$idU = $_POST['idU'];
			
			$queryC = "UPDATE `gauchada` SET `calificacionid`='$cal', `comentarioCal`='$com' WHERE `idGauchada`='$idG';";
			
			accion($queryC);

			$puntaje = fetch("SELECT puntaje FROM usuario
							where idUsuario=$idU",true);

			if ($cal==5) {

				#sumo credito
				
				$query = "INSERT INTO compracredito (`usuarioid`, `preciocreditoid`) VALUES ('$idU', 0);";
				
				$res = accion($query, true);

				$puntos=$puntaje['puntaje']+1;

			} else if ($cal==3) {

				$puntos=$puntaje['puntaje']-2;

			} else if ($cal==4){
				$puntos=$puntaje['puntaje'];
			}

			$query= "UPDATE `usuario` SET `puntaje`='$puntos' WHERE `idUsuario`='$idU';";
			accion($query);

			header("location:gauchada.php?id=$idG&m=2");

		} else { 
			if (isset($_POST['comentario']) && $_POST['calificacion']==0) {
			$error=1; }
		}
	}

 #####################################################################
	
	$usuarioElegido = $_POST['idU'];

	if (isset($_GET['idU'])){
		$usuarioElegido=$_GET['idU'];
	}
	
	
	$idG = $_POST['idG'];  
	
	$query = "SELECT * FROM usuario as u
			WHERE u.idUsuario=$usuarioElegido";

	$elegido = fetch($query,true); 

		
	
	$queryG = "SELECT * FROM gauchada as g 
  				WHERE g.idGauchada=$idG";
  	
  	$gauchada = fetch($queryG,true);
  			

	$queryCal = "SELECT * FROM calificacion as c
				WHERE c.idCalificacion>2 AND c.idCalificacion<6"; //Solo muestra neutral, bien y mal.
	$cal = fetch($queryCal); 

	$usuario = $elegido['nombre'].' '.$elegido['apellido']; 
	$titulo = $gauchada['titulo']; 
?>
<?php if ($_SESSION['logueado']) { ?>	
	<div class="menu">
		<h2 class="titulo">Calificar usuario</h2>
		<div id="volver">
	 		<a href="gauchada.php?id=<?php echo $idG ?>">Ir a gauchada</a>
	 	</div>
		<?php if ($error) {?>
				<h3 class="<?php echo $msj[$error]['type']; ?>"> 
				<?php echo $msj[$error]['text']; ?>	
				</h3>
		<?php } ?>
		
		<div id="calU">
			<p>Dejá tu comentario y calificá a <?php echo $usuario; ?> en la gauchada titulada "<?php echo $titulo; ?>".</p>
			
			<!--<form method="POST" action="calificar.php?idU=<?php echo $usuarioElegido ?>" >-->
			<form method="POST" action="calificar.php" >
				<div class="textarea">
					<textarea name="comentario" rows="4" cols="53" placeholder="¿Resolvió el problema como esperabas? ¿Por qué?" required><?php if (isset($_POST['comentario'])) {
						echo $_POST['comentario']; } ?></textarea><br><br>
				</div>
				<label>¿Cómo calificás la ayuda obtenida?</label>
				<select id="calificacion" name="calificacion">
					<option value="0">Seleccione</option>
					<?php foreach ($cal as $row) { ?>
						<option value="<?php echo $row['idcalificacion']; ?>"> <?php echo $row['calificacion']; ?></option>
				<?php } ?>	
				</select>
				<input type="hidden" name="idG" value="<?php echo $idG ?>">
				<input type="hidden" name="idU" value="<?php echo $usuarioElegido ?>">
				<button>Calificar</button>
			</form>
		</div>
	</div>
<?php } ?>

<?php require 'includes/footer.php'; ?>