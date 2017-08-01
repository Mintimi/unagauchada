<?php session_start(); ?>
<?php $pag='Postulantes'; ?>
<?php require 'includes/encabezadoLog.php'; ?>
<?php if ($_SESSION['logueado']) {
	$msj=array();
	$msj[1]['text'] = "Selección de postulante exitosa. Se enviaron los datos de contacto";
	$msj[1]['type'] = "ok";
	$msj[2]['text'] = "No se puede eliminar la gauchada por poseer postulantes";
	$msj[2]['type'] = "error";

	if (isset($_GET['m'])){
		$num=$_GET['m'];
	}
	

	if (isset($_GET['id'])) {
	
		$idG=$_GET['id'];
		

		if ($_GET['ord']=='ASC' || $_GET['ord']=='DESC') {
			$tipo = $_GET['ord'];
		} else { $tipo = "ASC"; }

		$orden = " ORDER BY po.creado $tipo;"; 

		if ($_GET['crit']=='creado' || $_GET['crit']=='puntaje') {
			$crit = $_GET['crit'];	
		} else { $crit = false; }

		if ($crit=='creado') { 
			$orden = " ORDER BY po.$crit $tipo;";
		}else if ($crit=='puntaje'){
			$orden = " ORDER BY u.$crit $tipo;";
		} 


		$query="SELECT 
						u.idUsuario,
						u.nombre,
						u.apellido,
						u.puntaje,
						po.comentario
					FROM
				    	postulante as po
				    LEFT JOIN
				   		gauchada as ga ON ga.idgauchada = $idG
					LEFT JOIN 
						usuario as u ON u.idUsuario = po.usuarioid
					WHERE
						po.gauchadaid = $idG $orden";

		$pos = fetch ($query);
       
	    /* 		[nombre] => Lulu
	            [apellido] => Lala
	            [mail] => lu@la
				[telefono] => 123456
				[comentario] => Soy un comentario */

		$query = "SELECT 
						ga.calificacionid,
    					ga.autor_usuarioId
					FROM
    					gauchada as ga
					WHERE
    					ga.idGauchada = $idG;";
    	$autor = fetch($query,true);

    	$queryUsuario="SELECT * FROM postulante as p
						WHERE p.estado=1 AND p.gauchadaid='$idG';";
		$usuarioPos = fetch($queryUsuario,true);

		$queryRep = "SELECT 
						r.idReputacion,
						r.reputacion,
						r.hasta
					FROM reputacion as r
					ORDER BY r.hasta ASC";

		$reputaciones = fetch($queryRep);

	?>	
	<div class="menu">
	 	<p><h3 class="titulo">Postulaciones</h3>
	 		<div id="volver"><a href="gauchada.php?id=<?php echo $idG ?>">Volver</a></div></p>
	 	<?php $idG=$_GET['id']; ?>
	 	<div class="<?php echo $msj[$num]['type']; ?>">
	 	<?php if (isset($_GET['m'])) {?>
	 		<h4 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h4>
	 	<?php } ?><br>
	 	</div>

	 	<div id="ordenar" style="margin-bottom:-5%;">

	 	 	<form method="GET" action="postulante.php">
		 		
		 		<p><label>Ordenar por: <select name="crit" id="crit" required></label>
					<option value="0" selected>Seleccione</option>			
					<option value="creado" <?php echo ($_GET['crit']=="creado" ) ? "selected" : '' ;?>>Fecha</option>	
					<option value="puntaje" <?php echo ($_GET['crit']=="puntaje" ) ? "selected" : '' ;?>>Reputación</option>	
				</select>
				
				<select name="ord" id="ordenar" required>
					<option value="0" selected>Seleccione</option>	
					<option value="ASC" <?php echo ($_GET['ord']=="ASC" ) ? "selected" : '' ;?>>Ascendente</option>		
					<option value="DESC" <?php echo ($_GET['ord']=="DESC" ) ? "selected" : '' ;?>>Descendente</option>
				</select>
				
				<input type="hidden" name="id" value="<?php echo $idG; ?>">
				<button>Ordenar</button></p>

			</form><br><br>
	 	</div>
	 	
		 	<section class="pos">
		 	<?php if (empty($pos)) {
		 		echo  "<h4 class='error'> Esta gauchada no tiene postulantes :( </h4>";
		 	} else { ?>
		 		<?php foreach ($pos as $usuario) {
		 			$uRow=$usuario['idUsuario'];
		 		 	$uPos=$usuarioPos['usuarioid'];
		 		 	
					$uRep=$usuario['puntaje'];
					foreach ($reputaciones as $rep) {
						if ($uRep<=$rep['hasta']) {
							$reputacion=$rep['reputacion'];
							break;
						}
					}
					
		 		 	?>
		 			<div id="usuario" style="width:100%;" class="<?php echo ($uRow==$uPos) ? "borde" : "negrito"; ?>" >
		 				<div>
		 					<h4 style="text-align: center;"><?php echo ($uRow==$uPos) ? "Usuario Elegido" : "" ; ?></h4>
		 				<div id="datos">
			 				<p><?php echo $usuario['nombre'].' '.$usuario['apellido']; ?> | Reputacion: <?php echo $reputacion;  ?> </p>
			 			</div>
			 			<div id='com'>
			 				<p><?php echo $usuario['comentario'] ?></p>
						</div>
			 			<?php if ($_SESSION['idUsuario']==$autor['autor_usuarioId']) { ?>
			 					<div id="opcionesPos">
					 				<a href="perfilPos.php?idG=<?php echo $idG?>&idU=<?php echo $usuario['idUsuario']?>">Ver perfil</a><br>
				 					<?php if ($usuarioPos==0) { ?>
				 						<div id="opcionesPos">
					 						<br><a href="elegirPos.php?idG=<?php echo $idG?>&idU=<?php echo $usuario['idUsuario']?>" class="boton">Elegir</a>
					 					</div>
				 					 <?php }
				 					 else if ($uRow==$uPos && !empty($usuarioPos)) { ?>
				 					 		<div id="elegido">
				 					 			
					 					 	 	<?php if ($autor['calificacionid']=='2') { ?>
					 							<form method="POST" action="calificar.php" id="colorBoton">
					 								<input type="hidden" name="idG" value="<?php echo $idG ?>">
					 								<input type="hidden" name="idU" value="<?php echo $usuarioElegido['usuarioid']; ?>">
					 								<button id="botoncito" style="margin-right: 60%;">Calificar</button>
					 							</form>			
					 							<?php } ?>
				 					 	 	</div>
				 					 	<?php  }  ?>
				 					 
			 					</div>
			 			<?php } ?>
			 		</div>

		 			</div><br>
		 		<?php } ?>
		 	<?php } ?>
	<?php } ?>
 	</section>
 
 </div>
 <?php require 'includes/footer.php'; ?>

 <?php } else { header("location:index.php"); } ?>