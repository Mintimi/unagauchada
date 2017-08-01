<?php session_start(); ?>
<?php $pag='Gestión'; 

$msj=array();
$msj[1]['text'] = "Modificación de precio exitosa";
$msj[1]['type'] = "ok";

if (isset($_GET['m'])) {
	$num = $_GET['m'];
} 
?>
<?php if ($_SESSION['logueado'] && $_SESSION['admin'] == 1) { ?>

	

	<?php require 'includes/encabezadoLog.php'; ?>
	<div class="menu">

	 	<p><h3 class="titulo">Administración</h3>
	 		

	 	
	 	<div class="mensaje">
	 	<?php if (isset($_GET['m'])) {?>
	 		<h3 class="<?php echo $msj[$num]['type'] ?>"><?php  echo $msj[$num]['text'];?></h3>
	 	<?php } ?>
	 	</div>
	 	<section id="vista">
	 		<div class="opcGestion">
	 			<a href="abmCat.php"style="margin-left:10%;">Categorías<br>
		 		<img src="img/categoria.png"><br></a>
	 		</div>
	 		<div class="opcGestion">
		 		<a href="abmRep.php">Reputaciones<br>
		 		<img src="img/reputacion.png"><br></a>
	 		</div>
	 		<div class="opcGestion">
	 			<a href="precioMod.php">Cambiar precio<br>
		 		<img src="img/creditos.png"><br></a>
	 		</div>
	 		<div class="opcGestion">
	 			<a href="abmAdmin.php">Administradores<br>
		 		<img src="img/admin.png"><br></a>
	 		</div>
	 		
	 	</section>

	</div>

<?php } ?>
<?php require 'includes/footer.php'; ?>