<?php
//include_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");

include_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/ComunesTrait.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");


class PDF_Tabla extends FPDI{
	use FormatoCeldas;
		
var $widths;
var $aligns;
var $formatUTF8 = false;
	private $AltoLinea = 5;
	private $DibujaFondo = false;
	private $DibujaFondoTexto = false;

	private $arrayIndexFormat = array();
    private $symbolFormat = '$ ';
    private $decimalFormat = 2;
    private $itemsFormat = 0;

public function SetAltoLinea($value){    
	$this->AltoLinea = $value;
}

public function SetDibujaFondoTexto($value){    
    $this->DibujaFondoTexto=$value;
}

public function SetDibujaFondo($value){    
    $this->DibujaFondo=$value;
}
public function SetWidths($w){    
    $this->widths=$w;
}
public function SetAligns($a){    
    $this->aligns=$a;
}

public function SetFormatUTF8($value){    
	//true or false
    $this->formatUTF8=$value;
}

public function SetFormatCell($arrayIndexFormat, $decimalFormat = 2, $symbolFormat = '$ '){    	
    $this->arrayIndexFormat=$arrayIndexFormat;
    $this->decimalFormat=$decimalFormat;
    $this->symbolFormat=$symbolFormat;
	
	if( isset($arrayIndexFormat) )
		$this->itemsFormat = count($arrayIndexFormat);
}

public function Row($data)
{    
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    //$h=5*$nb;    
    $h=$this->AltoLinea*$nb;    
    
    $this->CheckPageBreak($h);	
    
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        
        $x=$this->GetX();
        $y=$this->GetY();
		
		if($this->DibujaFondo and $i==0){
			$sAlt = $this->AltoLinea;
			$this->Rect($x, $y, $this->w-10, $sAlt, "F");
		}
				
		if($this->itemsFormat > 0){			
			if (in_array($i, $this->arrayIndexFormat)){			
				$data[$i] = toMoney( floatval($data[$i]));						
			}
		}
		
		if($this->formatUTF8){
			$str = utf8_decode($data[$i]);
			//$str = removeAccents($data[$i]);							
			$this->MultiCell($w, $this->AltoLinea, trim($str), 0, $a, $this->DibujaFondoTexto  );        
		}
		else
			$this->MultiCell($w, $this->AltoLinea, trim($data[$i]), 0, $a, false );        
			
        $this->SetXY($x+$w, $y);
    }    
    $this->Ln($h);
}

public function Print_Logo(){
	$this->Image($_SERVER["DOCUMENT_ROOT"]."/images/logo_2009_byn_chico.png",$this->w - 45, 3, 40 , 15);
}

public function CheckPageBreak($h)
{   	
	$x=$this->GetX();
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
	$this->SetX($x);
}

public function NbLines($w, $txt)
{    
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r", '', $txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

		
}