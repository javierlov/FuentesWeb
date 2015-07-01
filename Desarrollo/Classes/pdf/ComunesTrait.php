<?php

trait FormatoCeldas{

   public function ImprimeCelda($x, $y, $texto, $dato, $borde=0, $negrita=false, $maxAncho=0, $fontSize=8, $textAlign='L'){
		$espacios = (strlen($texto)*2)-3; 		
		if($maxAncho != 0) $espacios = $maxAncho;
		if($fontSize == 0) $fontSize = 10;  
		
		$this->SetXY($x,$y);  
		if($negrita)
			$this->SetFont('Arial' , 'B', $fontSize);
		else
			$this->SetFont('Arial' , '', $fontSize);
		$this->Cell($espacios,$this->AltoLinea,$texto,$borde,0,$textAlign);		
   }
   
   
    public function ImprimeLineaTextoMaxW($x, $y, $texto, $dato, $maxAncho=0, $borde=0, $styleFont='', $fontSizeTitulo = 10, $fontSizeDato = 9){
		$espacios = (strlen($texto)*2)-3; 		
		
		$this->SetXY($x,$y);  
		$this->SetFont('Arial' , 'B', $fontSizeTitulo);
		$this->Cell($espacios,$this->AltoLinea,$texto,$borde,1,'L');
		
		$espaciosDato = (strlen($dato)*2)+5; 
		if($maxAncho != 0) $espaciosDato = $maxAncho;
		
		$this->SetXY($x+$espacios,$y);  
		$this->SetFont('Arial' , $styleFont, $fontSizeDato);
		$this->Cell($espaciosDato,$this->AltoLinea,$dato,$borde,1,'L');		
   }
   
	public function ImprimeLineaTexto($x, $y, $texto, $dato, $borde=0, $styleFont=''){
		$espacios = (strlen($texto)*2)-3; 
		$this->SetXY($x,$y);  
		$this->SetFont('Arial' , 'B', 10);
		$this->Cell($espacios,$this->AltoLinea,$texto,$borde,1,'L');
		
		$espaciosDato = (strlen($dato)*2)+5; 
		$this->SetXY($x+$espacios,$y);  
		$this->SetFont('Arial' , $styleFont, 9);
		$this->Cell($espaciosDato,$this->AltoLinea,$dato,$borde,1,'L');		
   }
   
   public function ImprimeLineaTextoCheck($x, $y, $texto, $check, $borde){
		$espacios = (strlen($texto)*2); 
		
		$this->SetXY($x,$y);  
		$this->SetFont('Arial' , 'B', 10);
		$this->Cell($espacios-1,$this->AltoLinea,$texto,0,1,'L');
		
		$dato = '';
		$check=strtoupper(trim($check));		
		if( $check == 'S' || $check == 'X' ) $dato = 'X';
		
		$this->SetXY($x+$espacios,$y);  
		$this->SetFont('Arial' , '', 12);
		$this->Cell(5,$this->AltoLinea,$dato,1,1,'L');		
   }
   
   public function ImprimeLineaTextoColumna($x, $y, $texto, $dato, $borde, $fondoGris, $maxAncho=0){
		
		$espacios = (strlen($texto)*2)-3; 
		$anchofijo = 40;
		
		if($fondoGris){
			$this->SetFillColor(192, 192, 192);
			$this->Rect($x, $y+0.2, $anchofijo, 5, "F");
			$this->SetFillColor(0, 0, 0);
		}
		$this->SetXY($x,$y);  
		$this->SetFont('Arial' , 'B', 9);
		$this->Cell($anchofijo,$this->AltoLinea,$texto,$borde,1,'L');

		$fontSize = 8;
		$espaciosDato = (strlen($dato)*2)+5; 		
		if($maxAncho != 0) {
			$espaciosDato = $maxAncho;
			//$fontSize = 7;
		}
		
		if($espaciosDato < 150) {
			$this->SetXY($x+$anchofijo,$y);  
			$this->SetFont('Arial' , 'I', $fontSize);		
			$this->Cell($espaciosDato,$this->AltoLinea,$dato,$borde,1,'L');		
		}else{
			$this->SetXY($x+$anchofijo,$y);  
			$this->SetFont('Arial' , 'I', 7);		
			$this->Cell(150,$this->AltoLinea,$dato,$borde,1,'L');		
		}
    }   
	
    public function LineaSepara(){		
		$longline = $this->w - 5;
		//$posY = $this->GetY() + 0.2;	
		$posY = $this->GetY();	
		$posX = $this->GetX();	
		$this->line($posX - 4.6, $posY, $longline, $posY);
    }
	
	public function RellenaFondoLinea($r, $g, $b, $autoajustar=false){										

		$xA=$this->GetX();
		
		if($autoajustar){
			$this->SetX(5);
		}
		
		$this->SetFillColor($r, $g, $b);
		$x=$this->GetX();
		$y=$this->GetY();
		$anchofijo = $this->w - 10;
		$this->Rect($x, $y+0.2, $anchofijo, 5, "F");
		$this->SetFillColor(0, 0, 0);
		$this->SetX($xA);
		
    }
	
	function TrimAndUTF8($Value){		
		return trim(utf8_decode($Value));
    }
}