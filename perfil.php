<?php session_start(); ?>
<?php $pag='Perfil'; ?>
<?php require 'includes/encabezadoLog.php'; 

	if (isset($_GET['msj'])) { 
		$msj = $_GET['msj']; 
	} else { $msj = false; } 

	$mensaje = array();
	$mensaje[1]['text'] = 'Compra realizada con éxito'; 
	$mensaje[1]['type'] = 'ok';
	$mensaje[2]['text'] = 'Ingrese una fecha de nacimiento válida';
	$mensaje[2]['type'] = 'error';
	$mensaje[3]['text'] = 'Datos modificados'; 
	$mensaje[3]['type'] = 'ok';
	$mensaje[4]['text'] = 'Ingrese un foto válida';
	$mensaje[4]['type'] = 'error';
	$mensaje[5]['text'] = 'Foto modificada';
	$mensaje[5]['type'] = 'ok';
	$mensaje[6]['text'] = 'E-mail ya registrado';
	$mensaje[6]['type'] = 'error';
	$mensaje[7]['text'] = 'Las contraseñas no coinciden'; 
	$mensaje[7]['type'] = 'error'; 
	$mensaje[8]['text'] = 'Contraseña cambiada'; 
	$mensaje[8]['type'] = 'ok';  
	?>

<?php if ($_SESSION['logueado']) {


	if (isset($_GET['op']) && $_GET['op']=='mod') { ?>
		<div class="menu">
			<?php if (isset($msj)) {?>
			<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
			<?php } ?>
			<h3 class="titulo">Modificar datos</h3>
			<?php $id = $_SESSION['idUsuario'] ?>
			<?php $query="SELECT * FROM usuario WHERE idUsuario=$id;";
			  $result = fetch($query,true);  ?>
			<section id="perfil">
				<form method="POST" action="modificarPef.php?op=mod">
					<label>Nombre:
					<input type="text" name="nombre" placeholder="Ingrese su nombre" value="<?php echo $result['nombre']; ?>" required>
					</label><br><br>
					<label>Apellido:
					<input type="text" name="apellido" placeholder="Ingrese su apellido" value="<?php echo $result['apellido']; ?>"required></label><br><br>
					<label>E-mail:
					<input type="email" name="email" placeholder="Ingrese su e-mail" value="<?php echo $result['mail']; ?>" required></label><br><br>
					<label>Teléfono:
					<input type="text" name="tel" placeholder="Ingrese su número" value="<?php echo $result['telefono']; ?>" maxlength="10" required></label><br><br>
					<label>Fecha de Nacimiento:
					<input type="date" name="fecha" placeholder="DD-MM-AAAA" maxlength="10" value="<?php echo $result['nacimiento']; ?>" required></label><br><br>
					<input type="hidden" name="idU" value="<?php echo $id ?>">
					<button>Guardar Cambios</button>
				</form>
			</section>
			<div id="opcM">
				<a href="perfil.php">Volver</a><br><br>
				<a href="perfil.php?op=foto">Cambiar foto</a><br><br>
				<a href="perfil.php?op=pass">Cambiar contraseña</a>
			</div>
		</div>
	<?php } else if (isset($_GET['op']) && $_GET['op']=='foto') { ?>
				<div class="menu">
					<?php if (isset($msj)) {?>
				<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
				<?php } ?>
					<h3 class="titulo">Cambiar Foto</h3>
					<div id="vol">
						<a href="perfil.php">Volver</a>
					</div>
					<?php $id = $_SESSION['idUsuario'] ?>
					
					<div id="perfilM">
						<form method="POST" action="modificarPef.php?op=foto" enctype="multipart/form-data">
							<label>Foto:					
							<input name="foto" type="file" accept="image/*"></label>
							<button>Guardar</button>
							<input type="hidden" name="idU" value="<?php echo $id ?>">
						</form>
					</div>
					<!--<div id="volverM">
						<a href="perfil.php">Volver</a>
					</div>-->
				</div>
	<?php } else if (isset($_GET['op']) && $_GET['op']=='pass') { ?>
				<div class="menu">
				<?php if (isset($msj)) {?>
					<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
				<?php } ?>
					<h3 class="titulo">Cambiar contraseña</h3>
					<?php $id = $_SESSION['idUsuario'] ?>
					<div id="perfil">
						<form method="POST" action="modificarPef.php?op=pass">
							<label>Contraseña:
							<input type="password" name="clave" placeholder="Ingrese una contraseña" required></label><br><br>
							<label>Repetir contraseña:
							<input type="password" name="claveRep" placeholder="Repita la contraseña" required></label><br><br>
							<input type="hidden" name="idU" value="<?php echo $id ?>">
							<button>Guardar</button>
						</form>
					</div>
					<div id="volverM">
						<a href="perfil.php">Volver</a>
					</div>
				</div>
	<?php	} else {
	$id=$_SESSION['idUsuario'];
	$query="SELECT * FROM usuario WHERE idUsuario=$id;";
	$result = fetch($query,true); 
	$query = "SELECT 
				    ss . *, 
					dd . *, 
					(ss.comprados - dd.gastados + 1) as resultado
				FROM
				    (SELECT 
				        IFNULL(SUM(pc.creditos),0) as comprados
				    FROM
				        preciocredito as pc
				    LEFT JOIN compracredito as cc ON pc.idpreciocredito = cc.preciocreditoid
				    WHERE
				        cc.usuarioid = $id) as ss
				        JOIN
				    (SELECT 
				        count(*) as gastados
				    FROM
				        gauchada
				    WHERE
				        autor_usuarioid = $id) as dd";
	$creditos= fetch($query,true);


	$queryRep = "SELECT 
					r.idReputacion,
					r.reputacion,
					r.hasta
				FROM reputacion as r
				ORDER BY r.hasta ASC";

	$reputaciones = fetch($queryRep);

	$uRep=$result['puntaje'];
	foreach ($reputaciones as $rep) {
		if ($uRep<=$rep['hasta']) {
			$reputacion=$rep['reputacion'];
			break;
		}
	}
	?>


	<div class="menuPerfil">
		<?php if (isset($msj)) {?>
			<h3 class="<?php echo $mensaje[$msj]['type']; ?>" style="margin-left: 12%" ><?php echo $mensaje[$msj]['text']; ?></h3>
		<?php } ?>
		<?php 
		$fecha = $result['nacimiento'];
		$split = explode('-', $fecha);
		$fecha = $split[2].'-'.$split[1].'-'.$split[0]; ?>
		<section id="perfil">
			<h3 class="nombre"><?php echo  $result['nombre'], ' ' , $result['apellido']; ?></h3>
			<div id="imagen">
				<img src="<?php 
						echo ($result['foto']) ? $result['foto'] : 'img/logo.png' ;?>">
			</div>
				  
			<p>Reputación: <?php echo $reputacion ?> (<?php echo $uRep ?>)</p>
			<p>E-mail: <?php echo $result['mail'] ?></p>
			<p>Teléfono: <?php echo $result['telefono'] ?></p>
			<p>Créditos disponibles: <?php echo $creditos['resultado']; ?></p>
			<p>Fecha de nacimiento: <?php echo $fecha ?></p>
		</section>
		<div id="opc">
			<a href="mensajeria.php">Mis mensajes</a><br><br>
			<a href="misGauchadas.php">Mis guachadas</a><br><br>
			<a href="misPostulaciones.php">Mis postulaciones</a><br><br>
			<a href="perfil.php?op=mod">Modificar datos</a>
			
		</div>
	</div>
 <?php } ?>
	<?php require 'includes/footer.php'; ?>

<?php } else { header("location:index.php"); } ?>