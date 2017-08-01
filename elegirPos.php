<?php
session_start();

include 'bd.php';


$idG=$_GET['idG']; 
$idU=$_GET['idU'];

$query="SELECT foto,titulo FROM gauchada WHERE idGauchada=$idG";
$gauchada=fetch($query,true);


$query="UPDATE `postulante` SET `estado`= 1 
		WHERE `usuarioid`='$idU' AND `gauchadaid`='$idG';";
accion($query); 

$query="SELECT mail,
			   telefono,
			   nombre,
			   apellido,
			   idUsuario
		    FROM usuario WHERE idUsuario=$idU";
$usuarioElegido=fetch($query,true);

$idPos=$usuarioElegido['idUsuario'];
$titulo=$gauchada['titulo'];
$foto=$gauchada['foto'];
$tel=$_SESSION['telefono'];
$mail=$_SESSION['mail'];
$id=$_SESSION['idUsuario'];
$nombre=$_SESSION['nombre'].' '.$_SESSION['apellido'];
$telElegido=$usuarioElegido['telefono'];
$mailElegido=$usuarioElegido['mail'];
$nomEle=$usuarioElegido['nombre'].' '.$usuarioElegido['apellido'];
//intercambio de datos usuario elegido



$admin_email = "sol.fagot@gmail.com";
$email = $usuarioElegido['mail'];
$subject = "Postulación aceptada";
$comment = "El usuario $nombre ha aceptado tu postulación para $titulo ! Contactate a: $tel o $mail " ;
mail($email, $subject, $comment);

$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`, `categoria`) VALUES ('$idPos', '$subject', '$comment', '1')";
$idMsj=accion($query,true);
if ($gauchada['foto']=!NULL) {
	$query="UPDATE `mensaje` SET `foto`='$foto' WHERE `idmensaje`='$idMsj'";
	accion($query);
}




//intercambio de datos usuario elegido

$admin_email = "sol.fagot@gmail.com";
$email = $_SESSION['mail'];
$subject = "Datos usuario elegido";
$comment = "Has elegido al usuario $nomEle para $titulo Télefono: $telElegido E-mail: $mailElegido" ;
mail($email, $subject, $comment);

$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`, `categoria`) VALUES ('$id', '$subject', '$comment', '3')";
$idMsj=accion($query,true);
if ($gauchada['foto']=!NULL) {
	$query="UPDATE `mensaje` SET `foto`='$foto' WHERE `idmensaje`='$idMsj'";
	accion($query);
}



$queryG ="UPDATE `gauchada` SET `calificacionid`='1' 
					WHERE `idGauchada`='$idG';";
accion($queryG);


//marco como rechazados a todos los demás postulantes

$postulantes="SELECT 
						u.idUsuario,
						u.mail	
					FROM
				    	postulante as po
				    LEFT JOIN
				   		gauchada as ga ON ga.idgauchada = $idG
					LEFT JOIN 
						usuario as u ON u.idUsuario = po.usuarioid
					WHERE
						po.gauchadaid = $idG AND po.estado=0;";
$pos = fetch($postulantes);
foreach ($pos as $row) {
	$idP = $row['idUsuario'];
	$queryLoca = "UPDATE `postulante` SET `estado`='3' 
					WHERE `usuarioid`='$idP' AND gauchadaid=$idG;";
	accion($queryLoca);
	$admin_email = "sol.fagot@gmail.com";
	$email = $row['mail'];
	$subject = "Postulación rechazada";
	$comment = "El usuario $nombre ha rechazado tu postulación para $titulo" ;
	mail($email, $subject, $comment);

	$query="INSERT INTO `mensaje` (`usuarioId`, `titulo`, `contenido`, `categoria`) VALUES ('$idP', '$subject', '$comment', '2')";
	$idMsj=accion($query,true);
	if ($gauchada['foto']=!NULL) {
		$query="UPDATE `mensaje` SET `foto`='$foto' WHERE `idmensaje`='$idMsj'";
		accion($query);
	}

}

header("location:postulante.php?id=$idG&m=1"); ?>