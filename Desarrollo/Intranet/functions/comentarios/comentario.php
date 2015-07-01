<?
function agregarCodigoComentario($idModulo, $idArticulo, $titulo) {
?>
<iframe id="iframeProcesandoComentario" name="iframeProcesandoComentario" src="" style="display:none;"></iframe>
<form action="/functions/comentarios/guardar_comentario.php" id="formComentario" method="post" name="formComentario" target="iframeProcesandoComentario">
	<input id="idarticulo" name="idarticulo" type="hidden" value="<?= $idArticulo?>" />
	<input id="idmodulo" name="idmodulo" type="hidden" value="<?= $idModulo?>" />
	<input id="titulo" name="titulo" type="hidden" value="<?= $titulo?>" />
	<input id="url" name="url" type="hidden" value="<?= $_SERVER["REQUEST_URI"]?>" />
	<div><textarea id="comentario" name="comentario"></textarea></div>
	<div id="divBotones">
		<input id="btnComentar" name="btnComentar" type="submit" value="" />
	</div>
</form>
<?
}
?>