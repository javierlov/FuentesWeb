<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");

/*
Clase preparada para exportar un query pasado como parámetro al formato elegido.
Se configura pasandole una serie de parámetros al constructor..

La forma básica de uso sería como muestra el ejemplo de abajo:

$sql = "SELECT * FROM dual";
$exportQuery = new ExportQuery($sql, "Estadisticas_Informe_de_Gestion");
$exportQuery->export();
*/

class ExportQuery {
	private $fieldAlignment = array();		// Indica como van a estar alineados los campos en el excel..
	private $fieldNamesStyle = "background-color:#0f539c; border-color:#666; border-width:1px; border-style:solid; color:white; font-family:Neo Sans; font-size:12px; height:20px;";		// Estilo de los títulos de las columnas..
	private $fieldValuesStyle = "border-color:#666; border-width:1px; border-style:solid; font-family:Neo Sans; font-size:12px; height:20px;";		// Estilo de los valores de las columnas..
	private $fileName = "";		// Indica el nombre del archivo que se exportará..
	private $fileType = 1;		// Indica a que formato se exportará el archivo. 1-Excel 2-Word 3-?..
	private $header = "";		// Cabecera a mostrarse antes de la grilla..
	private $showFieldNames = true;		// Indica si hay que exportar el nombre de los campos o no..
	private $sql = "";		// Query pasado como parámetro..


	public function __construct($sql, $fileName, $fileType = 1, $showFieldNames = true) {
		// Constructor..

		$this->fileName = $fileName;
		$this->fileType = $fileType;
		$this->showFieldNames = $showFieldNames;
		$this->sql = $sql;
	}

	public function export() {
		// Método encargado de exportar el query a excel..

		global $conn;

		header("Content-type: ".$this->getHeader()."; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=".basename($this->fileName.$this->getExtension()));
		header("Pragma: no-cache");
		header("Expires: 0");

		$result = $this->header;
		$result.= "<table border=1>";

		$stmt = DBExecSql($conn, $this->sql);
		if (DBGetRecordCount($stmt) > 0) {
			$cols = 0;
			while($row = DBGetQuery($stmt, 0)) {
				// Exporto el nombre de las columnas..
				if ($cols == 0) {
					$cols = count($row);
					if ($this->showFieldNames) {
						$result.= "<tr>";
						for ($i=1; $i<=$cols; $i++) {
							$col_name = OCIColumnName($stmt, $i);
							if (substr($col_name, 0, 3) != "NO_") {
								$alineacion = "left";
								if (isset($this->fieldAlignment[$i - 1]))
									$alineacion = $this->fieldAlignment[$i - 1];

								$result.= "<th align=".$alineacion." style='".$this->fieldNamesStyle."'>".htmlspecialchars($col_name)."</th>";
							}
						}
						$result.= "</tr>";
					}
				}

				// Exporto el valor de los campos..
				$result.= "<tr>";
				for ($i=0; $i<$cols; $i++) {
					$col_name = OCIColumnName($stmt, $i + 1);
					if (substr($col_name, 0, 3) != "NO_") {
						$alineacion = "left";
						if (isset($this->fieldAlignment[$i]))
							$alineacion = $this->fieldAlignment[$i];

						$result.= "<td align=".$alineacion." style='".$this->fieldValuesStyle."'>".get_htmlspecialchars($row[$i])."</td>";
					}
				}
				$result.= "</tr>";
			}
		}
		else
			$result.= "<tr><td>No hay registros para exportar.</td></tr>";

		$result.= "</table>";

		echo $result;
	}

	private function GetExtension() {
		// Método que devuelve la extenssión del archivo a exportar..

		switch ($this->fileType) {
			case 1:
				return ".xls";
				break;
			case 2:
				return ".doc";
				break;
			case 3:
//				return ".???";
				break;
		}
	}

	private function GetHeader() {
		// Método que devuelve el tipo de header que se necesita según el tipo de archivo a exportar..

		switch ($this->fileType) {
			case 1:
				return "application/vnd-ms-excel";
				break;
			case 2:
				return "application/vnd.ms-word";
				break;
			case 3:
//				return "?";
				break;
		}
	}


	public function setFieldAlignment($value) {
		$this->fieldAlignment = $value;
	}

	public function setFieldNamesStyle($value) {
		$this->fieldNamesStyle = $value;
	}

	public function setFieldValuesStyle($value) {
		$this->fieldValuesStyle = $value;
	}

	public function setHeader($value) {
		$this->header = $value;
	}
}
?>