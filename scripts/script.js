
function fun(val,pre){
	//document.getElementById("oculto").value = val * 50;
	val = val * pre;
	$('#oculto').val(val);
}

function showComment(id) {
	$('#resp'+id).toggle();

}