<?php session_start(); ?>
<?php $pag='Gestión'; 
require 'includes/encabezadoLog.php';
if ($_SESSION['logueado'] && $_SESSION['admin'] == 1) { 
	if (isset($_GET['op']) && $_GET['op']=='mod') {
		$precio=$_POST['precio'];
		$query="UPDATE `preciocredito` SET `precio`='$precio' WHERE `idPrecioCredito`='1';";
		accion($query);
		header('location:gestion.php?m=1');
	}
	

?> <div class="menu">

	 	<p><h3 class="titulo" id="titulo">Cambiar precio de los créditos</h3>	 	
	 	<div id="volver" class="margenTop"><a href="gestion.php">Volver</a></div></p>
	 	
	 	<?php 
	 	$query="SELECT precio FROM precioCredito 
	 	WHERE idPrecioCredito=1";

	 	$precio=fetch($query,true);

	 	 ?>

	 	<div id="cajita" class='top'>
	 		<p>Precio actual: $<?php echo $precio['precio']; ?> </p>
	 		<form action="precioMod.php?op=mod" method="POST">
	 			<label>Precio nuevo:</label>
	 			<input type="number" name="precio" style="width: 40px;" required min="1">
	 			<button>Cambiar</button>
			</form>
	 	</div>
<?php } ?>
<?php require 'includes/footer.php'; ?>