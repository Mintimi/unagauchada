<?php session_start(); ?>
<?php $pag='Gestión'; 
require 'includes/encabezadoLog.php'; 
$msj =  array();
$msj[1]['text'] = 'Rango existente';
$msj[1]['type'] = 'error';
$msj[2]['text'] = 'Reputación existente';
$msj[2]['type'] = 'error';
$msj[3]['text'] = 'Reputación agregada exitosamente';
$msj[3]['type'] = 'ok';
$msj[4]['text'] = 'Reputación modificada';
$msj[4]['type'] = 'ok';
$msj[5]['text'] = 'No hubo modificación';
$msj[5]['type'] = 'ok';
$msj[6]['text'] = 'Reputación eliminada';
$msj[6]['type'] = 'ok';

if (isset($_GET['m'])) {
	$num = $_GET['m'];
} 

 if ($_SESSION['logueado'] && $_SESSION['admin'] == 1) { 

 	if (isset($_GET['op']) && $_GET['op']=="new") {
 		$rep=$_POST['rep'];
 		$hasta=$_POST['hasta'];
 		$rep=strtolower($rep);
 		$rep=ucfirst($rep);
 		$query="SELECT reputacion FROM reputacion WHERE hasta='$hasta'";
 		$query2="SELECT reputacion FROM reputacion WHERE reputacion='$rep'";
 		$existe=fetch($query,true);
 		$existe2=fetch($query2,true);

 		if ($existe) {
 			header('location:abmRep.php?m=1'); exit();
 		}else if ($existe2){
 			header('location:abmRep.php?m=2'); exit();
 		}else {
 			$query="INSERT INTO `reputacion` (`reputacion`, `hasta`) VALUES ('$rep', '$hasta')";
 			accion($query);
 			header('location:abmRep.php?m=3');			
 		}
 	} else if ($_GET['op']=="mod") {
 			$id=$_GET['id']; 
 			$query="SELECT  idReputacion,
 							reputacion,
 							hasta
 					FROM reputacion WHERE idReputacion='$id'";
 			$repu=fetch($query,true);

 			if (isset($_GET['form']) && $_GET['form']=='ok') {

 				$id=$_POST['id'];
 				$rep=$_POST['rep'];
 				$hasta=$_POST['hasta'];
		 		$rep=strtolower($rep);
		 		$rep=ucfirst($rep);

				$query="SELECT  idReputacion,
 								reputacion,
 								hasta
 						FROM reputacion WHERE idReputacion='$id'";
 				$existe=fetch($query,true);
 				$query="SELECT  idReputacion FROM reputacion WHERE reputacion='$rep'";
 				$existe1=fetch($query,true);
 				$query="SELECT idReputacion FROM reputacion WHERE hasta='$hasta'";
 				$existe2=fetch($query,true);

 				if ($existe['reputacion']==$rep && $existe['hasta']==$hasta ) {
 				
 					$existe=false;
 					$existe2=false;
 					header('location:abmRep.php?m=5'); exit();

 				} else if ($existe1 && $existe1['idReputacion']!=$id) {
 					header("location:abmRep.php?op=mod&m=2&id=$id");
 				} else if (!empty($existe2) && $existe2['idReputacion']!=$id) {
 					header("location:abmRep.php?op=mod&m=1&id=$id");
 				} else if (empty($existe1) && empty($existe2) || $existe['reputacion']==$rep && empty($existe2) || $existe['hasta']==$hasta && empty($existe1)) {
 					$query="UPDATE `reputacion` SET `reputacion`='$rep', `hasta`='$hasta' WHERE `idReputacion`='$id'";
 					accion($query);
 					header('location:abmRep.php?m=4');
 				} 
 				

 			}

 			?>

 			<div class="menu">
		 	<p><h3 class="titulo">Modificar Reputación</h3>
		 	<div id="volver">
			 	<a href="abmRep.php">Volver</a>
			 </div>			 	
		 	<div>
		 		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h3>
		 	</div>
		</div>
		<div id="cajita">
			<form method="POST" action="abmRep.php?op=mod&form=ok">
				<p><label>Reputación: </label><input type="text" name="rep" placeholder="Ingrese reputación nueva" value="<?php echo $repu['reputacion']; ?>" required>
				<label>Hasta: </label><input type="number" min="1" style="width: 37px" name="hasta" placeholder="Ingrese número" value="<?php echo $repu['hasta']; ?>" style="width: 108px" required>
				<input type="hidden" name="id" value="<?php echo $repu['idReputacion']; ?>">
				<button>Modificar</button>
			</form>
		</div>
 <?php 	} else if ($_GET['op']=="del"){  
 		$id=$_GET['id'];
 		$query="DELETE FROM `reputacion` WHERE `idReputacion`='$id';";
 		accion($query);
 		header('location:abmRep.php?m=6'); exit();

} else { ?>
 	<div class="menu">
	 	<p><h3 class="titulo">Reputaciones</h3>
	 	<div id="volver">
		 	<a href="gestion.php">Volver</a>
		 </div>			 	
	 	<div>
	 		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h3>
	 	</div>
	</div>
	<section id="cajita">
		<div id="agregar">
			<form method="POST" action="abmRep.php?op=new">
				<p><label>Reputación: </label><input type="text" name="rep" placeholder="Ingrese reputación nueva" required>
				<label>Hasta: </label><input type="number"  min="1" style="width: 105px" name="hasta" placeholder="Ingrese número"  required>
				<button>Agregar</button><br></p>
			</form>
		</div>
		<?php $query="SELECT reputacion,
							idReputacion,
							hasta
						FROM reputacion ORDER BY hasta ASC";
		$rep=fetch($query);
		$i=0;
		$size=count($rep);
		foreach ($rep as $value) { ?>
			<?php if ($i==0 && $value['hasta']<0){
				$desde='-∞';
			} else { $desde=$rep[$i-1]['hasta']+1; }
			?>

			<div id="contR">
					<p><?php echo $value['reputacion']; ?> |
					Rango: <?php if($value['idReputacion']=='5'){ 
								echo $value['hasta']; 
							} else { echo ($desde<0) ? "(" : "" ; echo $desde; echo ($desde<0) ? ")" : "" ; echo ($value['hasta']<0) ? " - (" : " - " ; echo($i==$size-1) ? "(+∞)" : $value['hasta']; echo ($value['hasta']<0) ? ")" : "" ; 
							}?></p>
					<div id="opt" class="margen">
					 <?php if ($value['idReputacion']!='5' && $value['idReputacion']!='3') { ?>
							<a href="abmRep.php?op=mod&id=<?php echo $value['idReputacion']; ?>">Modificar</a>
							<a href="abmRep.php?op=del&id=<?php echo $value['idReputacion']; ?>">Eliminar</a>
				<?php	} ?>
						</div>	
			</div>
			<?php $i++; ?>
	<?php }  ?>


		 
	</section>


<?php } } ?>

<?php require 'includes/footer.php'; ?>