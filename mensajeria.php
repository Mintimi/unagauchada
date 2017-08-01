<?php session_start(); ?>
<?php $pag='Mensajes'; ?>
<?php require 'includes/encabezadoLog.php'; ?>
<?php if ($_SESSION['logueado']) { 

	$idU = $_SESSION['idUsuario']; 

	if (isset($_GET['crit'])) {
		
		$traer = ' AND 1=1';
		
		if ($_GET['crit'] == 'elegido') {
			$traer = " AND categoria='3'";
		} else if ($_GET['crit'] == 'aceptado') {
			$traer = " AND categoria='1'";
		}else if ($_GET['crit'] == 'rechazado') {
			$traer = " AND categoria=2";
		}else if ($_GET['crit'] == 'admin') {
			$traer = " AND categoria='4'";
		}

	}

	$query="SELECT
				titulo,
				contenido,
				fecha,
				foto
			FROM mensaje where usuarioId=$idU $traer ORDER BY fecha DESC";
	$mensajes = fetch($query); ?>
	

	<div class="menuPerfil">
			<p><h3 class="titulo">Mis mensajes</h3></p><br>
			 <div id="volverPos">
			 	<a href="perfil.php">Volver</a>
			 </div>
	</div>
	
	<div id="ordenarPor">
			<form method="GET" action="mensajeria.php">
		 		
		 		<p><label>Ver mensajes: <select name="crit" id="crit" required></label>
					<option value="0" selected>Todos</option>			
					<option value="elegido" <?php echo ($_GET['crit']=="elegido" ) ? "selected" : '' ;?>>Usuarios elegidos</option>	
					<option value="aceptado" <?php echo ($_GET['crit']=="aceptado" ) ? "selected" : '' ;?>>Postulaciones aceptadas</option>	
					<option value="rechazado" <?php echo ($_GET['crit']=="rechazado" ) ? "selected" : '' ;?>>Postulaciones rechazadas</option>	
					<option value="admin" <?php echo ($_GET['crit']=="admin" ) ? "selected" : '' ;?>>Administración</option>	
				</select>

				<button>Buscar</button></p>

			</form><br><br>

	</div>


	 <div id="cajaGauchadas">
	 	<?php  
	 	if ($mensajes!=NULL) {
		 	
		 	foreach ($mensajes as $msj) { 
		 		$entera=explode(' ', $msj['fecha']);
		 		$fecha=$entera[0];
		 		$horaEntera=$entera[1];
		 		$hora=explode(':', $horaEntera);
		 		$hora=$hora[0].':'.$hora[1];
		 		$fecha=explode('-', $fecha);
		 		$bien=$fecha[2].'-'.$fecha[1].'-17'.'  '.$hora;
		 	 	
		 		?>

				<div class="individual">
					
					<div class="cont" style="width:100%;">
						<div class="imagen" style="float:left;margin-left:-81%;margin-top:9%;margin-right:10%; width:100%" >
							<img src="<?php 
							echo ($msj['foto']) ? $msj['foto'] : 'img/logo.png' ;?>"><br>
						</div>
						<h6 style="float:right;"><?php echo $bien; ?></h6>
						<h3><?php echo $msj['titulo'];?></h3>
						<p><?php echo $msj['contenido']; ?></p>
					</div>
				</div>
				
			<?php } 

			} else if (count($msj)==0) { ?>
		
				<div id="vacio" style="margin-bottom:10%; margin-top:10%;" >
					<?php  	echo "<h4 class='error'>No hay mensajes para mostrar </h4>";?>
				</div>
			<?php } ?>
		</div>
<?php require 'includes/footer.php'; ?>

<?php } else { header("location:index.php"); } ?>