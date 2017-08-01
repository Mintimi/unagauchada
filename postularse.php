<?php session_start(); ?>
<?php $pag='Postulantes'; ?>
<?php require 'includes/encabezadoLog.php'; ?>

<?php if ($_SESSION['logueado']) { 
	
	if (isset($_POST['comentario'])) {
		
		$com = $_POST['comentario'];
		$idG = $_POST['idG'];
		$usuario = $_SESSION['idUsuario'];

		$query = "INSERT INTO `postulante` (`usuarioid`, `gauchadaid`, `comentario`, `estado`) 
					VALUES ('$usuario', '$idG', '$com', '0');";

		accion($query);

		$query = "SELECT cantPost from gauchada 
					WHERE idGauchada=$idG";
		$cantidad = fetch($query,true);

		$cant=$cantidad['cantPost']+1;

		$query = "UPDATE `mydb`.`gauchada` SET `cantPost`=$cant WHERE `idGauchada`=$idG;";
		accion($query);

		header("location:gauchada.php?id=$idG&m=1");	
	
	} else { ?> 
		
		<div class="menu">
	 	<h3 class="titulo">Postulaciones</h3>
	 	<?php $idG=$_GET['id']; ?>
	 	<div id="volver">
	 		<a href="gauchada.php?id=<?php echo $idG ?>">Volver</a>
	 	</div>
	 	<div id='post'>
	 		<form method="POST" action="postularse.php">
 				<div class="textarea">
 					<p>¿Por qué quiere realizar esta gauchada?</p>
					<textarea name="comentario" rows="4" cols="40" placeholder="El autor leerá los comentarios de cada postulante y así podrá elegir al mejor candidato." required></textarea><br><br>
				</div>
				<input type="hidden" name="idG" value="<?php echo $idG ?>">
				<button>Postularme</button>
 			</form>
	 	</div>
	 	</div>
	<?php } ?>
<?php require 'includes/footer.php'; ?>
<?php } else { header("location:index.php"); } ?>
