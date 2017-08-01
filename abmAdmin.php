<?php session_start(); ?>
<?php $pag='Gestión'; 
require 'includes/encabezadoLog.php';

$msj[1]['text'] = "Eliminación exitosa.";
$msj[1]['type'] = "ok";  
$msj[2]['text'] = "Alta de administrador exitosa.";
$msj[2]['type'] = "ok";  ?>
 
<?php if (isset($_GET['m'])) {
	$num = $_GET['m'];
} ?>
<?php $titulo="Administradores"; $pag="gestion.php"?>
<?php if (isset($_GET['op'])){
		$titulo="Usuarios";
		$pag="abmAdmin.php";
	} ?>

<div class="menu">
		 	<p><h3 class="titulo"><?php echo $titulo; ?></h3>
		 	<div id="volver">
			 	<a href="<?php echo $pag; ?>">Volver</a>
			 </div>			 	
		 	<div>
		 		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h3>
		 	</div>
</div>

<?php if ($_SESSION['logueado'] && $_SESSION['admin'] == 1) { 

		if (isset($_GET['op']) && $_GET['op']=='del') {
			$id=$_GET['id'];
			$query="UPDATE `usuario` SET `nivelUsuario`='0' WHERE `idUsuario`='$id';";
			accion($query);
			$subject="Has dejado la administración";
			$comment="Ya no sos más usuario administrador.";
			$foto="img/add.png";
			$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`, `foto`, `categoria`) VALUES ('$id', '$subject', '$comment', '$foto', '4')";
			accion($query);
			if ($id==$_SESSION['idUsuario']) {
				session_destroy();
				header("location:index.php?m=3"); 
			} else {	header("location:abmAdmin.php?m=1"); }
		}
		if (isset($_GET['op']) && $_GET['op']=='add') {
			
			if (isset($_GET['id'])) {
				$id=$_GET['id'];
				$query="UPDATE `usuario` SET `nivelUsuario`='1' WHERE `idUsuario`='$id';";
				accion($query);
				$subject="Sos usuario administrador!";
				$comment="De ahora en más tendrás todos los beneficios de administración :D";
				$foto="img/add.png";
				$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`, `foto`, `categoria`) VALUES ('$id', '$subject', '$comment', '$foto', '4')";
				accion($query);
				
				header("location:abmAdmin.php?op=add&m=2");
			}

			$query="SELECT nombre,
							 apellido,
							 mail,
							 nivelUsuario,
							idUsuario
					FROM usuario";
			$usuarios=fetch($query); ?>

			<div id="cajita">
		<?php foreach ($usuarios as $value) { ?>
			<div id='cont' style="width:80%;">
				<p style="width:60%;"><?php echo $value['nombre'],' ', $value['apellido'], ' | ', $value['mail']; ?></p>
				<?php if ($value['nivelUsuario']==0) { ?>
					<div id="opt" class="opt" style="margin-top:-8%;">
						<a href="abmAdmin.php?op=add&id=<?php echo $value['idUsuario']; ?>">Hacer admin</a>
					</div>
					
				<?php } else { ?> <p style="float:right; color:orange; margin-top: -9%; margin-right:-5%; font-weight: bolder;"> Administrador </p> <?php } ?>
			</div>
			<?php }  ?>
			</div>
			
		<?php } else {
?>


	<section id="cajita">
		<div id="admin">
			<a href="abmAdmin.php?op=add">Agregar administrador</a>
		</div>
		<?php $query="SELECT nombre,
							 apellido,
							 mail,
							idUsuario
						FROM usuario WHERE nivelusuario=1";
		$admins=fetch($query);
		foreach ($admins as $value) { ?>
			<div id="cont" style="width:80%;">
				<p style="width:50%;"><?php echo $value['nombre'],' ', $value['apellido'], ' | ', $value['mail']; ?></p>
				<?php if (count($admins)>1) { ?>
					<div id="opt" style="margin-top: -7%;">
							<?php $op=($_SESSION['idUsuario']==$value['idUsuario']) ? "Dejar de ser admin" : "Eliminar" ; ?>
							<a href="abmAdmin.php?op=del&id=<?php echo $value['idUsuario']; ?>"><?php echo $op; ?></a>
					</div>	
					
			<?php } ?>
			</div>

		<?php }  ?>
		 
	</section>
<?php } ?>

<?php } ?>
<?php require 'includes/footer.php'; ?>