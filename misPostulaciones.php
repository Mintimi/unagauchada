<?php session_start(); 
$pag='Postulaciones';
require 'includes/encabezadoLog.php';
if ($_SESSION['logueado']) { 
	
	$idU=$_SESSION['idUsuario']; 

	if (isset($_GET['crit'])) {

		$traer = ' AND 1=1';
		
		if ($_GET['crit'] == 'pendiente') {
			$traer = " AND g.calificacionid='0' AND p.estado=0 ";
		} else if ($_GET['crit'] == 'rechazada') {
			$traer = " AND p.estado='3'";
		}else if ($_GET['crit'] == 'aceptada') {
			$traer = " AND p.estado='1' AND g.calificacionid<'3'";
		}else if ($_GET['crit'] == 'calificada') {
			$traer = " AND p.estado='1' AND g.calificacionid>2";
		}
	}


	?>


	<div class="menuPerfil">
		<p><h3 class="titulo">Mis Postulaciones</h3><br>
		 <div id="volverPos">
		 	<a href="perfil.php">Volver</a>
		 </div>
		 <?php if ($error) {?>
				<h3 class="<?php echo $msj[$error]['type']; ?>"><?php echo $msj[$error]['text']; ?></h3>
		<?php } ?>
		<div id="ordenarPor">
			<form method="GET" action="misPostulaciones.php">
		 		
		 		<p><label>Ver mis postulaciones: <select name="crit" id="crit" required></label>
					<option value="0" selected>Todas</option>			
					<option value="pendiente" <?php echo ($_GET['crit']=="pendiente" ) ? "selected" : '' ;?>>Pendientes</option>	
					<option value="rechazada" <?php echo ($_GET['crit']=="rechazada" ) ? "selected" : '' ;?>>Rechazadas</option>	
					<option value="aceptada" <?php echo ($_GET['crit']=="aceptada" ) ? "selected" : '' ;?>>Aceptadas</option>	
					<option value="calificada" <?php echo ($_GET['crit']=="calificada" ) ? "selected" : '' ;?>>Calificadas</option>	
				</select>

				<button>Buscar</button></p>

			</form><br><br>

		</div>
		 <div id="cajaGauchadas">
				
			<?php $queryG = "SELECT *
								FROM gauchada as g
	       						LEFT JOIN postulante as p 
	       						ON g.idGauchada = p.gauchadaid
								WHERE p.usuarioid = $idU AND g.calificacionid < 6 $traer";
			$gauchadas = fetch ($queryG); 

			#Postulante elegido


			if ($gauchadas!=NULL) {

				foreach ($gauchadas as $rowG) {
						
					$idG=$rowG['idGauchada'];

					$query="SELECT p.estado,
								   p.comentario,
								   g.calificacionid
							FROM postulante as p 
							LEFT JOIN gauchada as g ON g.idGauchada=$idG
							WHERE p.gauchadaid=$idG and p.usuarioid=$idU";
					$estado=fetch($query,true);

					if ($estado['estado']==3) {
						$calificacion="Rechazado";
					}
					if ($estado['estado']==1 && $estado['calificacionid']==1) {
						$calificacion="Aceptado";
					}
					if ($estado['estado']==1 && $estado['calificacionid']==2) {
						$calificacion="Sin calificar";
					}
					if ($estado['estado']==0) {
						$calificacion="Pendiente";
					}
					if ($estado['estado']==1 && $estado['calificacionid']==3) {
						$calificacion="Mal";
					}
					if ($estado['estado']==1 && $estado['calificacionid']==4) {
						$calificacion="Neutral";
					}
					if ($estado['estado']==1 && $estado['calificacionid']==5) {
						$calificacion="Bien";
					}

				 ?>
					<div class="individual">
						<?php $string="Calificado: ";   ?>
						<div class="cont">
							<h5><?php if ($calificacion=="Califique al usuario") { ?>
									
									<form method="POST" action="calificar.php" id="colorBoton">
	 								<input type="hidden" name="idG" value="<?php echo $idG ?>">
	 								<input type="hidden" name="idU" value="<?php echo $usuarioElegido['usuarioid']; ?>">
	 								<button id="botoncitoRaro">Califique al usuario</button>
	 							</form>	
						<?php } else if($calificacion=="Bien" || $calificacion=="Mal" || $calificacion=="Neutral") { echo $string."".$calificacion; }else { echo $calificacion; } ?> | Título: <?php echo $rowG['titulo']; ?> </h5>
							<p><?php echo $rowG['descripcion'] ?></p>
							<a href="gauchada.php?id=<?php echo $idG ?>&a=2">Ver</a>
						</div>
						<div class="imagen">
							<img src="<?php 
							echo ($rowG['foto']) ? $rowG['foto'] : 'img/logo.png' ;?>"><br>
						</div>
					</div>
				<?php }
				
			} else if (count($gauchadas)==0) { ?>
				<div id="vacio" style="margin-bottom:10%; margin-top:10%;" >
					<?php  	echo "<h4 class='error'>No se encuentran gauchadas.</h4>";?>
				</div>
		<?php }

			?>

		</div>


	</div>
	
<?php } else { header("index.php"); } ?>
<?php require 'includes/footer.php'; ?>