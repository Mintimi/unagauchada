<?php session_start(); ?>
<?php $pag='Home'; ?>
<?php require 'includes/encabezadoLog.php';
$error = false;
$msj =  array();
$msj[1]['text'] = "Se ha eliminado la gauchada correctamente.";
$msj[1]['type'] = "ok"; 
$msj[2]['text'] = "Se ha eliminado la gauchada correctamente y se ha informado al postulante elegido.";
$msj[2]['type'] = "ok";
$msj[3]['text'] = "Te has eliminado como administrador.";
$msj[3]['type'] = "ok"
?>
<?php if (isset($_GET['m'])) {
	$num = $_GET['m'];
} ?>
<?php 

	###########Califico gauchadas finalizadas###########
	
	$queryFin = "SELECT idGauchada FROM gauchada
						WHERE date(now())>=date(fecha) AND calificacionid<2"; #trae id g fecha menor a hoy
	$finIds = fetch($queryFin);
	

	if (!empty($finIds)) {
		foreach ($finIds as $vencida) {
			
			$idFin = $vencida['idGauchada'];
			$query = "UPDATE`gauchada` SET `calificacionid`='2' #Calificacion 2 = finalizada
						WHERE `idGauchada`='$idFin';";
			accion($query);
		}
	}


	$entrar = false;
	$titulo = false;
	$categoria = false;
	$zona = false;

	if ($_GET['ord']=='ASC' || $_GET['ord']=='DESC') {
		$tipo = $_GET['ord'];
	}

	if (isset($_GET['titulo'])) {
		$titulo = $_GET['titulo'];
		$tituloBien = $titulo; 
		$entrar = true;
	}

	if (isset($_GET['cat'])) {
		$categoria = $_GET['cat']; 
		$entrar = true;
	}

	if (isset($_GET['zona'])) {
		$zona = $_GET['zona'];
		$entrar = true;
	}

	if ($_GET['crit']=='titulo' || $_GET['crit']=='post' || $_GET['crit']=='fecha' || $_GET['crit']=='zona') {
		$crit = $_GET['crit'];
	} else { $crit=false; }


	$filtros = ($titulo || $categoria!=0 || $zona!=0) ? ' ' : '1=1';

	if ($entrar) {
		$contador = 0;

		if ($titulo) {
			$filtros .= "g.titulo like '%$tituloBien%'";
			$contador++;
		}

		if ($categoria) {
			$filtros .= ($contador!=0)? ' AND ': '';
			$filtros .= "gc.catId = $categoria";
			$contador++;
		}

		if ($zona) {
			$filtros .= ($contador!=0)? ' AND ': '';
			$filtros .= "gz.zonaId = $zona";
			$contador++;
		}

		if  ($tipo!='ASC' && $tipo!='DESC' ){ 
			$tipo='ASC'; 
		}

		if(!$crit){
			$tipo='ASC';
		}

		if ($crit=='titulo') { 
			$orden = " ORDER BY g.$crit $tipo;";
		} else if ($crit=='fecha') { 
			$orden = " ORDER BY g.$crit $tipo;";
		} else if ($crit=='zona') {
			$orden = " ORDER BY gz.zonaId $tipo;";
		}else if ($crit=='post') {
			$orden = " ORDER BY g.cantPost $tipo";
		}

		else {
			$orden = " ORDER BY g.idGauchada $tipo;";
		}
	}

	$query = "SELECT distinct
					g.idGauchada,
					g.titulo,
					g.foto,
					g.fecha,
					g.descripcion

				FROM
					gauchada as g
				LEFT JOIN gauchadacat as gc ON gc.gauchadaId=g.idGauchada
				LEFT JOIN gauchadazona as gz ON gz.gauchadaId=g.idGauchada
				WHERE g.calificacionid=0 AND $filtros $orden";

	/*		SELECT g.*,
	   				   postu.*
				from gauchada as g
				
				join(SELECT count(*) as post
						FROM postulante as p
						WHERE p.gauchadaId = 121) as postu
						where g.idGauchada=121*/

	$datos = fetch($query); ?>

				
	<div class="menu">
		<h3 class="titulo">Una Gauchada</h3>
		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php echo $msj[$num]['text']; ?></h3>
		<section id="filtro">
			<form method="GET" action="index.php">
				<p><label>Filtrar:</label>
				<input type="text" name="titulo" placeholder="Título" value="<?php if (isset($_GET['titulo'])) { echo $_GET['titulo']; } ?>">
				<select name="cat"> 
					<option value="0" selected>Categoria</option>
					<?php $categoria = fetch ('SELECT * FROM categoria');
										foreach ($categoria as $rowC) { ?>
					<option value="<?php echo $rowC['idCategoria']; ?>" <?php echo ($_GET['cat']!=0 && $_GET['cat']==$rowC['idCategoria']) ? "selected" : '' ;?>><?php echo $rowC['Categoria']; ?>
					</option>
					<?php } ?>
					</select>
					<select name="zona">
						<option value="0" selected>Zona</option>
						<?php $zona = fetch ('SELECT * FROM zona');
										foreach ($zona as $rowZ) { ?>
					<option value="<?php echo $rowZ['idZona']; ?>" <?php echo ($_GET['zona']!=0 && $_GET['zona']==$rowZ['idZona']) ? "selected" : '' ;?>><?php echo $rowZ['Zona']; ?></option>
					<?php } ?>
					</select>&nbsp;&nbsp;
					<select name="crit" id="crit" required>
						<option value="0" selected>Ordenar</option>									
						<option value="titulo" <?php echo ($_GET['crit']=="titulo" ) ? "selected" : '' ;?> >Título</option>			
						<option value="post" <?php echo ($_GET['crit']=="post" ) ? "selected" : '' ;?>>Postulantes</option>	
						<option value="fecha" <?php echo ($_GET['crit']=="fecha" ) ? "selected" : '' ;?>>Fecha</option>		
						<!--<option value="zona" <?php echo ($_GET['crit']=="zona" ) ? "selected" : '' ;?>>Zona</option>			-->						
					</select>
					<select name="ord" id="ordenar" required>
						<option value="0" selected>Seleccione</option>									
						<option value="ASC" <?php echo ($_GET['ord']=="ASC" ) ? "selected" : '' ;?> >Ascendente</option>					
						<option value="DESC" <?php echo ($_GET['ord']=="DESC" ) ? "selected" : '' ;?>>Descendente</option>							
					</select>
					<button>Buscar</button></p>
			</form>
		</section>
	<?php if (!empty($datos)) { ?>
		<section id="main">
		<?php foreach ($datos as $row) { ?>
				<?php $fecha = $row['fecha'];
				$split = explode('-', $fecha);
	 			$fechaBien = $split[2].'-'.$split[1].'-'.$split[0];?>
				<div class="gauchada">
					<div class="imagenG">
					<img src="<?php 
					echo ($row['foto']) ? $row['foto'] : 'img/logo.png' ;?>"><br>
					</div>
					<div class="contenidoG">
					<div class="tituloG">
						<a  href="gauchada.php?id=<?php echo $row['idGauchada'] ?>"><?php echo $row['titulo']; ?></a>
					</div>
					<p><?php echo $row['descripcion']; ?></p><br>
					<h4><?php echo $fechaBien; ?></h4>
					<a class="masG" href="gauchada.php?id=<?php echo $row['idGauchada'] ?>">Ver más</a>
					</div>
				</div><br>

			<?php } ?>
		<?php } else {?>
		<div id="vacio">
		<?php  	echo "<h4 class='error'>No hay gauchadas disponibles :( </h4>";?>
		</div>
	<?php } ?>
	
	</section>
</div>

<?php require 'includes/footer.php'; ?>