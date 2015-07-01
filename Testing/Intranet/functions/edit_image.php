<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


function editGifImage(&$file, $x1, $x2, $y1, $y2) {
	$im = imagecreatefromgif($file);
	$img = imagecreatetruecolor(($x2 - $x1), ($y2 - $y1));
	imagecopyresized($img, $im, 0, 0, $x1, $y1, ($x2 - $x1), ($y2 - $y1), ($x2 - $x1), ($y2 - $y1));

	$partes_ruta = pathinfo(strtolower($file));
	$file = $partes_ruta['dirname']."\\".date("YmdHis").".".$partes_ruta["extension"];

	imagegif($img, $file);
	imagedestroy($img);
}

function editJpgImage(&$file, $x1, $x2, $y1, $y2) {
	$im = imagecreatefromjpeg($file);
	$img = imagecreatetruecolor(($x2 - $x1), ($y2 - $y1));
	imagecopyresized($img, $im, 0, 0, $x1, $y1, ($x2 - $x1), ($y2 - $y1), ($x2 - $x1), ($y2 - $y1));

	$partes_ruta = pathinfo(strtolower($file));
	$file = $partes_ruta['dirname']."\\".date("YmdHis").".".$partes_ruta["extension"];

	imagejpeg($img, $file);
	imagedestroy($img);
}

function editPngImage(&$file, $x1, $x2, $y1, $y2) {
	$im = imagecreatefrompng($file);
	$img = imagecreatetruecolor(($x2 - $x1), ($y2 - $y1));
	imagecopyresized($img, $im, 0, 0, $x1, $y1, ($x2 - $x1), ($y2 - $y1), ($x2 - $x1), ($y2 - $y1));

	$partes_ruta = pathinfo(strtolower($file));
	$file = $partes_ruta['dirname']."\\".date("YmdHis").".".$partes_ruta["extension"];

	imagepng($img, $file);
	imagedestroy($img);
}

function uploadImage($arch, &$filename) {
	$tempfile = $arch["tmp_name"];
	$partes_ruta = pathinfo($arch["name"]);
	$filename = IMAGES_EDICION_PATH."\\".date("YmdHis").".".$partes_ruta["extension"];

	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, $filename))
			$uploadOk = true;

	if (!$uploadOk)
		echo "<script>alert('Ocurrió error al guardar la imagen.');</script>";

	return $uploadOk;
}

$file = "";
if (isset($_REQUEST["file"]))
	$file = $_REQUEST["file"];

$finalFunction = "";
if (isset($_REQUEST["finalFunction"]))
	$finalFunction = $_REQUEST["finalFunction"];

if (isset($_REQUEST["paso"]))
	$paso = $_REQUEST["paso"];
else
	$paso = 1;

if ($paso == 1)
	$margenLineaGris = 80;
else
	$margenLineaGris = 0;

if (($paso == 2) and (isset($_REQUEST["guardar"]))) {
	if (!uploadImage($_FILES["imagen"], $file))
		$paso = 1;
}

if ($paso == 3) {
	$partes_ruta = pathinfo(strtolower($file));
	if ($partes_ruta["extension"] == "gif")
		editGifImage($file, $_REQUEST["x1"], $_REQUEST["x2"], $_REQUEST["y1"], $_REQUEST["y2"]);
	if (($partes_ruta["extension"] == "jpg") or ($partes_ruta["extension"] == "jpeg"))
		editJpgImage($file, $_REQUEST["x1"], $_REQUEST["x2"], $_REQUEST["y1"], $_REQUEST["y2"]);
	if ($partes_ruta["extension"] == "png")
		editPngImage($file, $_REQUEST["x1"], $_REQUEST["x2"], $_REQUEST["y1"], $_REQUEST["y2"]);
}

if ($paso == 4) {
	if ($finalFunction != "") {
		$partes_ruta = pathinfo(strtolower($file));
?>
		<script>
			window.opener.<?= $finalFunction?>('<?= $partes_ruta['basename']?>');
			window.close();
		</script>
<?
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Editor de imagenes de Provincia A.R.T.</title>
		<script src="/js/validations.js" type="text/javascript"></script>
<?
if ($paso == 2) {
?>
		<script src="/js/scriptaculous/lib/prototype.js" type="text/javascript"></script>
		<script src="/js/scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
		<script src="/js/cropper/cropper.js" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
		function onEndCrop( coords, dimensions ) {
			$( 'x1' ).value = coords.x1;
			$( 'y1' ).value = coords.y1;
			$( 'x2' ).value = coords.x2;
			$( 'y2' ).value = coords.y2;
			$( 'width' ).value = dimensions.width;
			$( 'height' ).value = dimensions.height;
		}
		
		/*
		
		// example with minimum dimensions
		Event.observe( 
			window, 
			'load', 
			function() { 
				new Cropper.Img( 
					'testImage', 
					{ 
						minWidth: 200, 
						minHeight: 120,
						maxWidth: 200,
						//maxHeight: 120,
						displayOnInit: true, 
						onEndCrop: onEndCrop 
					} 
				) 
			} 
		);
		*/
		
		Event.observe( window, 'load',
			function() {
				Event.observe( 'dimensionsForm', 'submit', CropManager.attachCropper.bindAsEventListener( CropManager ) );
				CropManager.attachCropper();
			}
		);
		
		/**
		 * A little manager that allows us to reset the options dynamically
		 */
		var CropManager = {
			/**
			 * Holds the current Cropper.Img object
			 * @var obj
			 */
			curCrop: null,
			
			/**
			 * Gets a min/max parameter from the form 
			 * 
			 * @access private
			 * @param string Form element ID
			 * @return int
			 */
			getParam: function( name ) {
				var val = $F( name );
				return parseInt( val );
			},
									
			/** 
			 * Attaches/resets the image cropper
			 *
			 * @access private
			 * @param obj Event object
			 * @return void
			 */
			attachCropper: function( e ) {
				if( this.curCrop == null ) {
					this.curCrop = new Cropper.Img( 
						'testImage', 
						{ 
							minWidth: this.getParam( 'minWidth' ),
							minHeight: this.getParam( 'minHeight' ),
							maxWidth: this.getParam( 'maxWidth' ),
							maxHeight: this.getParam( 'maxHeight' ),
							onEndCrop: onEndCrop 
						} 
					);
				} else {
					this.removeCropper();
					this.curCrop = new Cropper.Img( 
						'testImage', 
						{ 
							minWidth: this.getParam( 'minWidth' ),
							minHeight: this.getParam( 'minHeight' ),
							maxWidth: this.getParam( 'maxWidth' ),
							maxHeight: this.getParam( 'maxHeight' ),
							onEndCrop: onEndCrop 
						} 
					);
				}
				if( e != null ) Event.stop( e );
			},
			
			/**
			 * Removes the cropper
			 *
			 * @access public
			 * @return void
			 */
			removeCropper: function() {
				if( this.curCrop != null ) {
					this.curCrop.remove();
					this.curCrop = null;
				}
			},
			
			/**
			 * Resets the cropper, either re-setting or re-applying
			 *
			 * @access public
			 * @return void
			 */
			resetCropper: function() {
				this.attachCropper();
			}
		};		
		</script>
<?
}
?>
		<style type="text/css">
			label {
				clear: left;
				margin-left: 50px;
				float: left;
				width: 5em;
			}

			#testWrap {
				margin: 20px 0 0 50px; /* Just while testing, to make sure we return the correct positions for the image & not the window */
			}

			#dimensionsForm {
				float: right;
				width: 350px;
			}
		</style>
	</head>
	<body bgcolor="#EEEEEE">
		<p style="margin-left: 12px"><img src="../modules/abm_arteria_noticias/images/HeaderEditorDeImagenes.gif"></p>
		<span style="font-family: Neo Sans; font-size:9pt; color:#000000; margin-left: 30px"">
<?
if ($paso == 1) {
?>
			Seleccione la imagen que quiere editar
<?
}
if ($paso == 2) {
?>
			Seleccione el sector de la imagen que desea conservar
<?
}
if ($paso == 3) {
?>
			Edici&oacute;n finalizada
<?
}
?>
		</span>

		<form action="#" id="dimensionsForm">
			<fieldset style="display:none;">
				Setear el cropper con las siguientes restricciones:
				<p>
					<label for="minWidth">Min Width</label>
					<input type="text" size="10" maxlength="3" id="minWidth" name="minWidth" value="<?= $_REQUEST["minWidth"]?>" />
				</p>	
				<p>
					<label for="maxWidth">Max Width</label>
					<input type="text" size="10" maxlength="3" id="maxWidth" name="maxWidth" value="<?= $_REQUEST["maxWidth"]?>" />
				</p>	
				<p>
					<label for="minHeight">Min Height</label>
					<input type="text" size="10" maxlength="3" id="minHeight" name="minHeight" value="<?= $_REQUEST["minHeight"]?>" />
				</p>	
				<p>
					<label for="maxHeight">Max Height</label>
					<input type="text" size="10" maxlength="3" id="maxHeight" name="maxHeight" value="<?= $_REQUEST["maxHeight"]?>" />
				</p>	
				<input type="submit" value="Set Cropper" />
			</fieldset>
		</form>
		<div id="testWrap">
			<div style="float:left; margin-left:-20px; margin-right:16px; margin-top:3px;">
				<p style="<?= (($paso == 1)?"background-color:#00A4E4; border: 1px solid #807F84; color:#FFFFFF; font-family: Neo Sans; font-size: 9pt;":"border: 1px solid #00A4E4; color:#8C8C8C; background-color: #FFFFFF; font-family: Neo Sans; font-size: 9pt;")?> cursor:default;">
					&nbsp;Paso 1&nbsp;
				</p>
				<p style="<?= (($paso == 2)?"background-color:#00A4E4; border: 1px solid #807F84; color:#FFFFFF; font-family: Neo Sans; font-size: 9pt;":"border: 1px solid #00A4E4; color:#8C8C8C; background-color: #FFFFFF; font-family: Neo Sans; font-size: 9pt;")?> cursor:default;">
					&nbsp;Paso 2&nbsp;
				</p>
				<p style="<?= (($paso == 3)?"background-color:#00A4E4; border: 1px solid #807F84; color:#FFFFFF; font-family: Neo Sans; font-size: 9pt;":"border: 1px solid #00A4E4; color:#8C8C8C; background-color: #FFFFFF; font-family: Neo Sans; font-size: 9pt;")?> cursor:default;">
					&nbsp;Paso 3&nbsp;
				</p>
			</div>
			<div id="#paso1" style="display:<?= (($paso == 1)?"block":"none")?>;">
				<form action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" id="formImagen1" method="post" name="formImagen1" onSubmit="return ValidarForm(formImagen1)">
					<input id="finalFunction" name="finalFunction" type="hidden" value="<?= $finalFunction?>">
					<input id="guardar" name="guardar" type="hidden" value="T">
					<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
					<input id="minWidth" name="minWidth" type="hidden" value="<?= $_REQUEST["minWidth"]?>" />
					<input id="maxWidth" name="maxWidth" type="hidden" value="<?= $_REQUEST["maxWidth"]?>" />
					<input id="minHeight" name="minHeight" type="hidden" value="<?= $_REQUEST["minHeight"]?>" />
					<input id="maxHeight" name="maxHeight" type="hidden" value="<?= $_REQUEST["maxHeight"]?>" />
					<input id="paso" name="paso" type="hidden" value="2">
					<input ext="gif,jpg,jpeg,png" id="imagen" name="imagen" style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 1px; padding-left: 4px;	padding-right: 4px;	padding-top: 1px;" size="40" title="Imagen" type="file" validar="true" validarImagen="true">
					<input type="submit" value="Subir imagen" style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 0px; padding-left: 4px;	padding-right: 4px;	padding-top: 0px;" />
				</form>
			</div>
			<div id="#paso2" style="display:<?= (($paso == 2)?"block":"none")?>;">
				<img id="testImage" src="<?= "/functions/get_image.php?file=".base64_encode($file)?>" />
				<form action="<?= $_SERVER["PHP_SELF"]?>" id="formImagen2" method="post" name="formImagen2" onSubmit="return ValidarForm(formImagen2)">
					<input id="file" name="file" type="hidden" value="<?= $file?>">
					<input id="finalFunction" name="finalFunction" type="hidden" value="<?= $finalFunction?>">
					<input id="paso" name="paso" type="hidden" value="3">
					<input type="submit" style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 0px; padding-left: 4px;	padding-right: 4px;	padding-top: 0px;" value="Guardar" />
					<div style="display:none;">
						<p>
							<label for="x1">x1:</label>
							<input type="text" name="x1" id="x1" />
						</p>
						<p>
							<label for="y1">y1:</label>
							<input type="text" name="y1" id="y1" />
						</p>
						<p>
							<label for="x2">x2:</label>
							<input type="text" name="x2" id="x2" />
						</p>
						<p>
							<label for="y2">y2:</label>
							<input type="text" name="y2" id="y2" />
						</p>
						<p>
							<label for="width">width:</label>
							<input type="text" name="width" id="width" />
						</p>
						<p>
							<label for="height">height</label>
							<input type="text" name="height" id="height" />
						</p>
					</div>
				</form>
			</div>
			<div id="#paso3" style="display:<?= (($paso == 3)?"block":"none")?>;">
				<form action="<?= $_SERVER["PHP_SELF"]?>" id="formImagen3" method="post" name="formImagen3" onSubmit="return ValidarForm(formImagen3)">
					<input id="file" name="file" type="hidden" value="<?= $file?>">
					<input id="finalFunction" name="finalFunction" type="hidden" value="<?= $finalFunction?>">
					<input id="paso" name="paso" type="hidden" value="4">
					<input type="submit" value="Finalizar" style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 0px; padding-left: 4px;	padding-right: 4px;	padding-top: 0px;" />
				</form>
				<img id="testImage" src="<?= "/functions/get_image.php?file=".base64_encode($file)?>" />
			</div>
		</div>
		<div style="left:-230px; position:relative; margin-top:<?= $margenLineaGris?>px;">
			<hr color="#807F84" width="500px" size="1" style="border-bottom-style:dotted; border-bottom-width: 1px; border-left-width:1px; border-right-width:1px; border-top-width:1px;">
		</div>
	</body>
</html>