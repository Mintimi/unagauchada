<?php session_start();?>

<?php $pag='Publicar Gauchada'; ?>
<?php require 'includes/encabezadoLog.php'; ?>

	
	<?php if (!empty($_POST)){

		$idU=$_SESSION['idUsuario'];
		/*$query="SELECT * from gauchada where
				autor_usuarioid=$idU and calificacionid=2";
		$pendiente=fetch($query,true);*/

		$queryCalificacion="SELECT calificacionid,
								SUM(if(calificacionid=2 AND cantPost>=1,1,0)) as resultado
							FROM gauchada 
							WHERE autor_usuarioid=$idU;";

		$calificacion=fetch($queryCalificacion,true);
		if ($calificacion['resultado']==0) {
			$calificadas=true;
		}else{
			$calificadas=false;
		}
		
		if (isset($_POST['titulo']) && isset($_POST['descripcion'])
		&& isset($_POST['fecha'])&& !empty($_POST['zona'])
		&& !empty($_POST['cat']) && $calificadas){

			$con = conexion();
			$titulo = mysqli_real_escape_string($con,$_POST['titulo']);
			$desc = mysqli_real_escape_string($con,$_POST['descripcion']);
			$fecha = mysqli_real_escape_string($con,$_POST['fecha']);
			$id = $_SESSION['idUsuario'];

			$cat = array();
			$zona = array();
			foreach ($_POST['zona'] as $value) {
				$zona[] = mysqli_real_escape_string($con,$value);
			}
			foreach ($_POST['cat'] as $value) {
				$cat[] = mysqli_real_escape_string($con,$value);
			}	

			//$fechaLimite = date($fecha);
    		$hoy = date("Y-m-d");
    		
			
			if ($hoy<$fecha) {

				$queryCreditos="
						SELECT 
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
						        autor_usuarioid = $id) as dd;";

				$creditos=fetch($queryCreditos,true);

				$poseeCreditos = ( $creditos['resultado']>0 ) ? true : false ;


				if ($poseeCreditos) {
					        
					$query = "INSERT INTO gauchada 
								(`autor_usuarioid`, `descripcion`, `titulo`, `fecha`, `calificacionid`, `cantPost`)VALUES 
								('$id', '$desc', '$titulo', '$fecha', '0', '0');";

					$res = accion($query, true);
					if($res || $res==0) {
						$lastInsertId = $res;
					}else { die();}
					

					foreach ($cat as $value) {
						$query = "INSERT INTO `gauchadacat` (`catId`, `gauchadaId`) VALUES 
									('$value', '$lastInsertId');";
						accion($query);
					}
					foreach ($zona as $value) {
						$query = "INSERT INTO `gauchadazona` (`zonaId`, `gauchadaId`) VALUES 
									('$value', '$lastInsertId');";
						accion($query);
					}
					

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
							//header("location:gauchada.php?e=2");
							$papita=2;
						} else {

							$query = "UPDATE `gauchada` 
										SET `foto`='$fotoFull' WHERE `idGauchada`='$lastInsertId';";
							accion($query);
							header("location:gauchada.php?id=$lastInsertId");
						}
					} header("location:gauchada.php?id=$lastInsertId"); 

				} else if (!$poseeCreditos) {
					header("location:tienda.php?e=1");	
				} 
				
			} else { //header("location:publicarG.php?e=1");
			$papita=1; 
		} 
	}else if (!$calificadas) {
		header('location:misGauchadas.php?crit=vencida&m=1');
	}
	
 	}  ?>
 	<?php if (isset($_SESSION['logueado'])){ ?>
 		<?php    
 			  /* if (isset($_POST['titulo'])) {
 			   	$titulo=$_POST['titulo'];
 			   }
 			   if (isset($_POST['descripcion'])) {
 			   	$descripcion=$_POST['descripcion'];
 			   } */?>
 		<?php if (isset($papita)) {
			$error = $papita; } else { $error=false;}
			$errorTipo = array();
			$errorTipo[1]['text'] = 'Ingrese una fecha válida';
			$errorTipo[1]['type'] = "error"; 
			$errorTipo[2]['text'] = 'Ingrese una foto con extensión jpg, png o jpeg';
			$errorTipo[2]['type'] = "error";
		?>
		<div>
			<section class="menu">
				<h3 class="titulo">Publicar gauchada</h3>
				<?php if ($error) {?>
					<h3 class="<?php echo $errorTipo[$error]['type']; ?>"> 
					<?php echo $errorTipo[$error]['text']; ?>	
					</h3>

				<?php } ?>
				<form method="POST" action="publicarG.php" enctype="multipart/form-data" >
					<label>Título:
					<input name="titulo" type="text" placeholder="Ingrese el título" value="<?php echo (isset($_POST['titulo'])) ? $_POST['titulo'] : '' ; ?>" required>
					</label><br><br>
					<div class="textarea">
						<label>Descripción:
						<textarea name="descripcion" rows="4" cols="40" placeholder="Ingrese descripción de la gauchada"required><?php echo (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '' ; ?></textarea>
						</label><br><br>
					</div>
					<label>Foto (opcional)</label>					
					<input name="foto" type="file" accept="image/*"><br><br>  
					<label>Fecha límite:
					<input name="fecha" type="date" placeholder="DD-MM-AAAA" maxlength="10"required>
					</label><br><br>
					<div id="zona">
						<label>Ingrese zona:</label><br><br>	
						<?php $variableZ = fetch ('SELECT * FROM zona');
						foreach ($variableZ as $rowZ) { ?>
						<input type="radio" name="zona[]" value="<?php echo $rowZ['idZona']; ?>" <?php echo ($rowZ['idZona']==$_POST['zona'][0]) ? 'checked' : ''; ?>><?php echo $rowZ['Zona']; ?><br>
						<?php } ?>
					</div><br>
					<div>
					<label>Ingrese categorías:</label><br><br>
						<?php $variableC = fetch ('SELECT * FROM categoria WHERE habilitado=1');
						foreach ($variableC as $rowC) { ?>
						<input type="checkbox" name="cat[]" value="<?php echo $rowC['idCategoria']; ?>" <?php 
							foreach ($_POST['cat'] as $categoria ) {
								if ($categoria==$rowC['idCategoria']) {
									echo 'checked';
								}
							}
						?>><?php echo $rowC['Categoria']; ?><br>	
						<?php } ?>
					</div><br><br>
					<button id="boton">Publicar</button>	
						
				</form>		
			</section>
		</div>
	<?php } else { header("location:index.php"); } ?>
<?php require 'includes/footer.php'; ?>