<?php session_start(); ?>
<?php $pag='Gauchada'; ?>
<?php require 'includes/encabezadoLog.php'; ?>

<?php 
$msj = array();
$msj[1]['text'] = "Te has postulado a esta guachada!";
$msj[1]['type'] = "ok";
$msj[2]['text'] = "Calificación exitosa";
$msj[2]['type'] = "ok";
$msj[3]['text'] = "Pregunta publicada";
$msj[3]['type'] = "ok";
$msj[4]['text'] = "Respuesta publicada";
$msj[4]['type']	= "ok";
$msj[5]['text'] = "Modificación exitosa";
$msj[5]['type'] = "ok";
$msj[6]['text'] = "Usuarios postulados, no se puede modificar";
$msj[6]['type'] = "error";
$msj[7]['text'] = "Se eliminó tu pregunta correctamente!";
$msj[7]['type'] = "ok";

if (isset($_GET['m'])) {
	$num=$_GET['m'];
}
$pag="index.php";
if (isset($_GET['a']) && $_GET['a']==1) {
	$pag="misGauchadas.php";
} else if (isset($_GET['a']) && $_GET['a']==2) {
	$pag="misPostulaciones.php";
}

if (isset($_GET['op']) && $_GET['op']=='del') {
	$idC=$_GET['id'];
	$idG=$_GET['idG'];
	$query="DELETE FROM `comentario` WHERE `idComentario`='$idC';";
	accion($query);
	header("Location:gauchada.php?id=$idG&m=7");
}

if (isset($_POST['pregunta'])) {

	$com = $_POST['pregunta'];
	$idG = $_POST['idG'];
	$idU = $_SESSION['idUsuario'];

	$query = "INSERT INTO `comentario` (`usuarioid`, `comentario`, `gauchadaid`) 
				VALUES ('$idU', '$com', '$idG');";

	accion($query);
	header("location:gauchada.php?id=$idG&m=3");
} 

if (isset($_POST['respuesta'])) {
	$resp = $_POST['respuesta'];
	$idC = $_POST['idC'];
	$idU = $_SESSION['idUsuario'];
	$idG = $_POST['idG'];
	$query = "INSERT INTO `respuesta` (`usuarioId`, `comentarioid`, `respuesta`) 
				VALUES ('$idU', '$idC', '$resp');";

	accion($query);
	
	header("location:gauchada.php?id=$idG&m=4");
}
 if (isset($_GET['id'])) {
	$idG=$_GET['id'];
	$idU=$_SESSION['idUsuario'];
		$query = "SELECT 
						ga.calificacionid,
					    ga.idGauchada,
					    ga.autor_usuarioid,
					    ga.descripcion,
					    ga.titulo,
					    ga.foto,
					    ga.fecha,
					    ga.comentarioCal,
					    u.nombre,
					    u.apellido,
					    u.telefono,
					    u.mail,
					    group_concat(DISTINCT ca.Categoria) as cat,
						group_concat(DISTINCT zo.Zona) as zona
					FROM
				    	gauchada as ga
				    LEFT JOIN
				   		gauchadacat as gc ON gc.gauchadaId = $idG
				    LEFT JOIN
				    	categoria as ca ON ca.idCategoria = gc.catId
					LEFT JOIN
						gauchadazona as gz on gz.gauchadaId = $idG
					LEFT JOIN 
						zona as zo ON zo.idZona=gz.zonaId
					LEFT JOIN 
						usuario as u ON u.idUsuario=ga.autor_usuarioid
					WHERE ga.idGauchada = $idG;";
	$datos = fetch($query);
		/*	[idGauchada] => 60
            [autor_usuarioid] => 19
            [descripcion] => Gauchada numero 3
            [titulo] => Gauchada 3
            [foto] => 
            [fecha] => 2018-04-27
            [cat] => Compras
            [zona] => La Plata,City Bell */

    $queryCom = "SELECT 
					    co.idComentario,
						co.comentario,
						co.usuarioid,
						re.respuesta,
						u.nombre,
						u.apellido,
						u.mail
					FROM
				    	gauchada as ga 
				    LEFT JOIN
				   		comentario as co ON co.gauchadaid = $idG
					LEFT JOIN 
						respuesta as re ON re.comentarioid = co.idcomentario
					LEFT JOIN
						usuario as u ON u.idUsuario = co.usuarioid
					WHERE
						ga.idGauchada = $idG
					ORDER BY co.creado DESC;";
    $com = fetch($queryCom);
    /*	
            [comentario] => Pffffff
            [respuesta] => Bbbbbb
            [nombre] => Mint
            [apellido] => Tea
            [mail] => min@ti */
    $queryUsuario="SELECT * FROM postulante as p
					WHERE p.usuarioid=$idU AND p.gauchadaid=$idG;";
	
	$usuarioPos = fetch($queryUsuario,true);


	$queryUsuarioElegido="SELECT * FROM postulante as p
						WHERE p.estado=1 AND p.gauchadaid='$idG';";
	$usuarioElegido = fetch($queryUsuarioElegido,true);

	$query="SELECT p.comentario, u.nombre, u.apellido from postulante as p
			LEFT JOIN usuario as u ON p.usuarioid=u.idUsuario
			WHERE p.gauchadaid=$idG AND p.estado=1";
	$infoPostulacion=fetch($query,true);
   ?> 
	<div class="menu">
	 	<h3 class="titulo">Una Gauchada</h3>
	 	<div>
			 	<a style="color:black; float:right; margin-bottom: -17%; margin-top:-5%;" href="<?php echo $pag; ?>">Volver</a>
		</div>	
	 	<h3 class="<?php echo $msj[$num]['type']; ?>"><?php echo $msj[$num]['text']; ?></h3>
	 	
	 	<section id="mainG">
	 		<?php foreach ($datos as $row) {
	 			$fecha = $row['fecha'];
	 			$split = explode('-', $fecha);
	 			$fechaBien = $split[2].'-'.$split[1].'-'.$split[0];
	 			$idAutor = $row['autor_usuarioid']; ?>
				<?php if ($row['calificacionid']>2) { ?> <!-- calificacion ya finalizada -->
	 				<h2 class="fin">GAUCHADA FINALIZADA</h2>
		 			<?php $idCal=$row['calificacionid'];
		 			$cal="SELECT calificacion FROM calificacion
								WHERE idCalificacion=$idCal";
					$calificacion=fetch($cal,true); ?>
	 			<?php } ?>
	 				<div class="ver"> 
	 					<?php if ($row['autor_usuarioid'] == $_SESSION['idUsuario']) {?>
	 						<a href="postulante.php?id=<?php echo $idG ?>">Ver postulaciones</a><br><br>
	 						<?php if ($row['calificacionid']==0 && $fecha>$hoy) { ?>
	 							<a href="delModG.php?op=mod&id=<?php echo $idG ?>">Modificar</a><br><br>
	 							
	 						<?php } ?>
	 						<?php $hoy = date("Y-m-d"); ?>
	 						<?php if (($row['calificacionid']<=2 && $fecha>$hoy) || (empty($usuarioElegido) && $fecha<=$hoy )) { ?>
	 							
	 							<a href="delModG.php?op=del&id=<?php echo $idG ?>">Eliminar</a><br><br>
	 						<?php } ?>
	 						<?php if ($row['calificacionid']=='2' && !empty($usuarioElegido)) { ?>
	 							<form method="POST" action="calificar.php" id="colorBoton">
	 								<input type="hidden" name="idG" value="<?php echo $idG ?>">
	 								<input type="hidden" name="idU" value="<?php echo $usuarioElegido['usuarioid']; ?>">
	 								<button id="botoncito">Calificar</button>
	 							</form>			
	 						<?php } ?>
	 					<?php } ?>
	 					<?php if ($_SESSION['idUsuario']!=$row['autor_usuarioid'] && count($usuarioPos)==0) { ?>
	 						<a href="postularse.php?id=<?php echo $idG ?>">Postularme</a>
	 					<?php } else if ($_SESSION['logueado'] && $_SESSION['idUsuario']!=$row['autor_usuarioid']){?> <p>Ya estás postulado</p>  <?php } ?>
	 				</div> 
				<div id="gauchadaU">
					<img src="<?php	echo ($row['foto']) ? $row['foto'] : 'img/logo.png' ;?>">
					<h3><?php echo $row['titulo']; ?></h3>	
					<?php if ($idCal>2) { ?>
								<div id="calificacionCajita">
									<p><h4>Calificacion: <?php echo $calificacion['calificacion']; ?></h4>
									Comentario: <?php echo $row['comentarioCal']; ?></p>
								</div> <br>
								<div id="calificacionCajita">
									<p><h4>Postulante: <?php echo $infoPostulacion['nombre'].' '.$infoPostulacion['apellido']; ?></h4>
									Comentario: <?php echo $infoPostulacion['comentario']; ?></p>	
								</div><br>
					<?php } ?>
					<h4>Descripción:</h4><p><?php echo $row['descripcion']; ?></p><br>
					<h4>Fecha límite: <?php echo $fechaBien; ?></h4>
					<p>Zona: <?php print_r($row['zona']); ?></p>
					<p>Categorias: <?php print_r($row['cat']); ?></p>
					<p>Autor: <?php echo $row['nombre'].' '.$row['apellido']; ?><br>
					<!--<br>E-mail: <?php echo $row['mail']; ?> - Teléfono: <?php echo $row['telefono']; ?>--></p>
					
				</div>

			<?php } ?>

			<div id="pregYres">
				<h4 class="tituloPR">Preguntas y Respuestas</h4>
				<div id="cuadroComentario">
					<?php if ($idU!=$idAutor && empty($usuarioElegido) && $_SESSION['logueado']) {	?>
						<form method="POST" action="gauchada.php">
							<div class="textarea">
								<label>Pregunta:
								<textarea name="pregunta" rows="4" cols="40" placeholder="Preguntá tus dudas sobre esta gauchada."required></textarea>
								</label><br><br>
								<input type="hidden" name="idG" value="<?php echo $idG ?>">
								<button>Enviar</button><br><br><hr><br>
							</div>
						</form>
					<?php } ?>
				</div>
				<?php foreach ($com as $data) { 
					$idC=$data['idComentario']; ?>
					<?php if ($data['comentario']!=NULL){?>
						<div id="coment"  class="<?php echo ($data['usuarioid']==$_SESSION['idUsuario']) ? "borde" : "negrito" ; ?>">
								
							<div id="preg" class="<?php echo (condition) ? a : b ; ?>">
								<p>Pregunta hecha por: <?php echo $data['nombre'].' '.$data['apellido']; ?><br></p>
								<p><?php echo $data['comentario']; ?></p>
								<?php if ($data['usuarioid']==$_SESSION['idUsuario'] && empty($data['respuesta'])) { ?>
										<a href="gauchada.php?op=del&id=<?php echo $data['idComentario'];?>&idG=<?php echo $row['idGauchada']; ?>" style="float:right; margin-top:-14%; color:black;" >Eliminar</a>
								<?php } ?>
								<?php if ($data['respuesta']!=NULL) { ?>
									<h4>Respuesta: </h4>
									<p><?php echo $data['respuesta']; ?></p> 	
							</div>
								<?php } else if ($row['autor_usuarioid'] == $_SESSION['idUsuario'] && $row['calificacionid']==0) { ?>
								 	<hr>
								 	<div class="resp">
								 		<a href="javascript:showComment(<?php echo $idC; ?>)" style="text-decoration:underline;">Responder</a>
								 	</div>
								 	<form method="POST" action="gauchada.php" style="display: none;" id="resp<?php echo $data['idComentario']; ?>">
								 			<textarea name="respuesta" rows="4" cols="40" placeholder="Escribí la respuesta a esta pregunta." required></textarea><br>
								 			<input type="hidden" name="idC" value="<?php echo $idC ?>">
								 			<input type="hidden" name="idG" value="<?php echo $idG ?>">
								 			<input type="submit" class="submit" value="Enviar">	
								 		</form><br>
								<?php }?>
						</div>
							
							<br>
						<?php } else { echo "No hay comentarios para mostrar";} ?>
			</div>
			<?php } ?>
			</div>		
		</section>
	<!--</div>-->
<?php } ?>	
<?php require 'includes/footer.php'; ?>

