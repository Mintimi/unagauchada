<?php session_start(); 
$pag='Postulante'; ?>
<?php require 'includes/encabezadoLog.php'; 
if ($_SESSION['logueado']) {
	$idG=$_GET['idG'];
	$idU=$_GET['idU'];

	$query = "SELECT 
				u.nombre,
				u.apellido,
				u.puntaje,
				u.nacimiento,
				u.foto,
				p.comentario,
				p.estado,
				p.usuarioid
			FROM usuario as u
			LEFT JOIN postulante as p
			ON p.usuarioId=$idU
			WHERE p.gauchadaId=$idG and u.idusuario=$idU";

	$postulante=fetch($query,true);

	
	$queryRep = "SELECT 
					r.idReputacion,
					r.reputacion,
					r.hasta
				FROM reputacion as r
				ORDER BY r.hasta ASC";

	$reputaciones = fetch($queryRep);

	$uRep=$postulante['puntaje'];
	foreach ($reputaciones as $rep) {
		if ($uRep<=$rep['hasta']) {
			$reputacion=$rep['reputacion'];
			break;
		}
	}

	
	 ?>

	 <?php  $fecha = $postulante['nacimiento'];
			$split = explode('-', $fecha);
	 		$fechaBien = $split[2].'-'.$split[1].'-'.$split[0];
	 ?>

	<div class="menuPerfil">
		<p><h3 class="titulo">Perfil del postulante</h3>
	 		<div id="volverPos">
	 			<a href="postulante.php?id=<?php echo $idG ?>">Volver</a><br><br>
	 			<?php if ($postulante['estado']==0) { ?>
	 					<a href="elegirPos.php?idG=<?php echo $idG?>&idU=<?php echo $idU ?>" class="boton">Elegir usuario</a>
	 			<?php } ?>

	 		</div></p>
		<?php if (isset($msj)) {?>
			<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
		<?php } ?>
		<section id="perfilPos">
			<div>
				<img src="<?php 
						echo ($postulante['foto']) ? $postulante['foto'] : 'img/logo.png' ;?>">
			</div>
			<div>
				<p><?php echo  $postulante['nombre'], ' ' , $postulante['apellido']; ?> | <?php echo $fechaBien; ?></p>

				<p>Reputación: <?php echo $reputacion ?>(<?php echo $uRep ?>)</p><br><br>
			</div>
		</section>
		<div id="cajaGauchadas">
			
			<?php $queryG = "SELECT p.gauchadaid
						FROM postulante as p 
						LEFT JOIN gauchada as g ON p.gauchadaid=g.idGauchada
						WHERE p.usuarioid=$idU AND p.estado=1 AND g.calificacionid<6 AND g.calificacionid>2";
			$ids = fetch ($queryG);

			if ($ids!=NULL) { 
					foreach ($ids as $idGauchada) {
						$id=$idGauchada['gauchadaid'];
						$query = "SELECT g.titulo,
									g.comentarioCal,
									g.foto,
								    c.calificacion
								FROM gauchada as g
								LEFT JOIN calificacion as c
								ON g.calificacionid=c.idcalificacion
								WHERE g.idGauchada=$id"; 
						$resultado = fetch($query,true); ?>
						<div class="individual">
							<div class="cont">
								<h5>Título: <?php echo $resultado['titulo']; ?> | Calificación: <?php echo $resultado['calificacion'] ?></h5>
								<p><?php echo $resultado['comentarioCal'] ?></p>
							</div>
							<div class="imagen">
								<img src="<?php 
								echo ($resultado['foto']) ? $resultado['foto'] : 'img/logo.png' ;?>"><br>
							</div>
						</div>
				<?php } 
			} else { ?>
			 	<div id="sinContenido">

					<h4>No hay gauchadas calificadas para mostrar.</h4> 
				</div>
			<?php } ?>
		</div>

	</div>

<?php require 'includes/footer.php'; ?>
<?php } else { header("location:index.php"); } ?>