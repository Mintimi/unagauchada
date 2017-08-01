<?php
session_start();
$pag='Modificar'; 

require 'includes/encabezadoLog.php'; 


if ($_SESSION['logueado']) {
	$mensaje = array();
	$mensaje[1]['text'] = "Ingrese una fecha válida";
	$mensaje[1]['type'] = 'error';
	$mensaje[2]['text'] = "Ingrese foto válida";
 	$mensaje[2]['type'] = 'error';

	if (isset($_GET['msj'])) {
		$msj=$_GET['msj'];
	}

	$idG=$_GET['id']; 


	$queryPost = "SELECT estado FROM postulante
					WHERE gauchadaid=$idG AND estado=0";
	$postulaciones = fetch($queryPost);


	$query = "SELECT p.gauchadaid,
					 p.usuarioid,
					 u.nombre,
					 u.apellido,
		 			 u.mail,
		 			 g.foto,
		 			 g.titulo
				FROM postulante as p
				LEFT JOIN usuario as u on u.idUsuario=p.usuarioid
				LEFT JOIN gauchada as g on g.idGauchada=$idG
				WHERE gauchadaid=$idG AND estado=1";

	$elegido = fetch($query,true);
	$titulo=$elegido['titulo'];
	$idPos=$elegido['usuarioid'];
	$foto=$elegido['foto'];
	$nombre=$_SESSION['nombre'].' '.$_SESSION['apellido'];
	
	$query="Select * from postulante where gauchadaid=$idG";
	$post=fetch($query);

	if ($_GET['op']=='del') {
		$idU=$_SESSION['idUsuario'];
	
	############# marco como eliminada la gauchada con calificacion = 6 #############
	
		$query = "UPDATE `gauchada` SET `calificacionid`='6' WHERE `idGauchada`='$idG';"; 
		accion($query);

		if (empty($post)) {		
			$query = "INSERT INTO compracredito (`usuarioid`, `preciocreditoid`) VALUES ('$idU', 0);";
			accion($query, true);
		}
		
		if (!empty($elegido)) {
	
			#informar al postulante
			

			#$admin_email = "sol.fagot@gmail.com";
			$email = $elegido['mail'];
			$subject = "Gauchada eliminada";
			$comment = "El usuario $nombre ha eliminado la gauchada $titulo";
	
			mail($email, $subject, $comment);
			$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`) VALUES ('$idPos', '$subject', '$comment')";
			$idMsj=accion($query,true);
			if ($elegido['foto']=!NULL) {
				$query="UPDATE `mensaje` SET `foto`='$foto' WHERE `idmensaje`='$idMsj'";
				accion($query);
			}

			header('location:index.php?m=2');exit();
	
		} else { header('location:index.php?m=1'); }
		

	
	}

	if ($_GET['op']=='foto') { 

		
		if (isset($_GET['o'])) {
			$idG = $_POST['idG'];
				
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
					header("delModG.php?op=foto&idG=$idG&msj=2"); 
				} else {

					$query = "UPDATE `gauchada` SET `foto`='$fotoFull' 
								WHERE `idGauchada`='$idG';";
					accion($query);
					header("location:gauchada.php?id=$idG&m=5");
				}
			} else if(empty($_FILES)) { header("location:delModG.php?op=foto&idG=$idG&msj=2"); exit;}
		}

		$idG = $_GET['idG']; ?>
		<div class='menu'>
			<h3 class="titulo">Cambiar Foto</h3>
			<?php if (isset($msj)) { ?>
					<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
				<?php } ?>
			<div id="vol">
				<a href="delModG.php?op=mod&id=<?php echo $idG ?>">Volver</a>
			</div>
			<?php $id = $_SESSION['idUsuario'] ?>
			<div id="perfilM">
				<form method="POST" action="delModG.php?op=foto&idG=<?php echo $idG ?>&o=modificar" enctype="multipart/form-data">
					<label>Foto:					
					<input name="foto" type="file" accept="image/*"></label>
					<input type="hidden" name="idG" value="<?php echo $idG ?>">
					<button>Guardar</button>
				</form>
			</div>
		</div>
<?php } 	

	if ($_GET['op']=='mod') {
		
		if (empty($postulaciones) && empty($elegido)) { 
			$query="SELECT 
						ga.calificacionid,
					    ga.idGauchada,
					    ga.descripcion,
					    ga.titulo,
					    ga.foto,
					    ga.fecha,
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
					WHERE ga.idGauchada = $idG;";
			$gauchada = fetch($query,true);

			if (isset($_GET['e'])) {


				if (isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['fecha']) && !empty($_POST['zona']) && !empty($_POST['cat'])){
					$con = conexion();
					$titulo = mysqli_real_escape_string($con,$_POST['titulo']);
					$desc = mysqli_real_escape_string($con,$_POST['descripcion']);
					$fecha = mysqli_real_escape_string($con,$_POST['fecha']);
					$idG = $_POST['idG'];

					$cat = array();
					$zona = array();


					foreach ($_POST['zona'] as $value) {
						$zona[] = mysqli_real_escape_string($con,$value);
					}
					foreach ($_POST['cat'] as $value) {
						$cat[] = mysqli_real_escape_string($con,$value);
					}	

					
		    		$hoy = date("Y-m-d");

		    		if ($hoy<$fecha) {

		    			
						#elimino las zonas de esta guachada

						$query= "DELETE FROM `gauchadazona` WHERE `gauchadaId`='$idG';";
						accion($query);


						#elimino las categorias de esta gauchada

						$query= "DELETE FROM `gauchadacat` WHERE `gauchadaId`='$idG';";
						accion($query);


						foreach ($cat as $value) {
							$query = "INSERT INTO `gauchadacat` (`catId`, `gauchadaId`) VALUES 
										('$value', '$idG');";
							accion($query);
						}
						foreach ($zona as $value) {
							$query = "INSERT INTO `gauchadazona` (`zonaId`, `gauchadaId`) VALUES 
										('$value', '$idG');";
							accion($query);
						}

						$query="UPDATE `gauchada` SET `descripcion`='$desc', `titulo`='$titulo', `fecha`='$fecha' WHERE `idGauchada`='$idG';";

						accion($query);
						header("location:gauchada.php?id=$idG&m=5");

		    		} else { header("location:delModG.php?op=mod&id=$idG&msj=1"); exit();}
				}
			}

			?>
			<div class="menu">
				<h3 class="titulo">Modificar gauchada</h3>
				<?php if (isset($msj)) { ?>
					<h3 class="<?php echo $mensaje[$msj]['type']; ?>"><?php echo $mensaje[$msj]['text']; ?></h3>
				<?php } ?>
				<div id="volver">
					<a href="gauchada.php?id=<?php echo $idG ?>">Volver</a><br><br>
					<a href="delModG.php?op=foto&idG=<?php echo $idG ?>">Cambiar foto</a>
				</div>
					<div id="imgM">
						<img src="<?php	echo ($gauchada['foto']) ? $gauchada['foto'] : 'img/logo.png' ;?>">
					</div>
					<div id="cuadrito">
						<form method="POST" action="delModG.php?op=mod&e=modificar" enctype="multipart/form-data" >
							<label>Título:
							<input name="titulo" type="text" placeholder="Ingrese el título" value="<?php echo $gauchada['titulo'] ?>" required>
							</label><br><br>
							<div class="textarea">
								<label>Descripción:
								<textarea name="descripcion" rows="4" cols="40" placeholder="Ingrese descripción de la gauchada"required><?php echo $gauchada['descripcion']  ?></textarea>
								</label><br><br>
							</div>
							<label>Fecha límite:
							<input name="fecha" type="date" placeholder="DD-MM-AAAA" maxlength="10" value="<?php echo $gauchada['fecha']; ?>"required>
							</label><br><br>
							<div id="zona">
								<label>Ingrese zona:</label><br><br>	
								<?php $variableZ = fetch ('SELECT * FROM zona');
								$zon = fetch("SELECT zonaId FROM gauchadazona WHERE gauchadaId=$idG",true);

								foreach ($variableZ as $rowZ) { ?>
								<input type="radio" name="zona[]" value="<?php echo $rowZ['idZona']; ?>" <?php echo ($rowZ['idZona']==$zon['zonaId']) ? 'checked' : ''; ?>><?php echo $rowZ['Zona']; ?><br>
								<?php } ?>
							</div><br>
							<div> 
							<label>Ingrese categorías:</label><br><br>
								<?php $variableC = fetch ('SELECT * FROM categoria');
								$cat = fetch("SELECT catId FROM gauchadacat WHERE gauchadaId=$idG");
								foreach ($variableC as $rowC) { ?>
								<input type="checkbox" name="cat[]" value="<?php echo $rowC['idCategoria']; ?>" <?php 
									foreach ($cat as $categoria ) {
										if ($categoria['catId']==$rowC['idCategoria']) {
											echo 'checked';
										}
									}
								?>><?php echo $rowC['Categoria']; ?><br>	
								<?php } ?>
							</div><br><br>
							<input type="hidden" name="idG" value="<?php echo $idG ?>">
							<button id="boton">Modificar</button>	
								
						</form>	
					</div>	
			</div>

		<?php } else { header("location:gauchada.php?id=$idG&m=6"); }
	}  ?>
	<?php require 'includes/footer.php';

} else { header('location:index.php');} ?>