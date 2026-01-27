<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('../FUNCIONES/pdf/fpdf.php');

class PDF extends FPDF
{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';

	function WriteHTML($html)
	{
		// Intérprete de HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Etiqueta
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extraer atributos
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
}


/*$json_data = file_get_contents('php://input');
$data      = json_decode($json_data, true);*/


$asignatura = $_POST['asignatura'];
$observaciones = $_POST['observaciones'];
$alumno = $_POST['alumno'];

// Recoger archivos
$imagen1 = $_FILES['imagen1'];
$imagen2 = $_FILES['imagen2'];


$uploadDir = 'uploads/';
$uploadFile1 = $uploadDir . basename($imagen1['name']);
$uploadFile2 = $uploadDir . basename($imagen2['name']);

move_uploaded_file($imagen1['tmp_name'], $uploadFile1);
move_uploaded_file($imagen2['tmp_name'], $uploadFile2);


$pdf       = new PDF('P', 'mm', 'A4');

$pdf->SetTitle('Evaluacion continua');

$pdf->AddPage();


$pdf->Image('../IMAGENES/logo_transparente.png', 155, 13, 40);

$pdf->SetFont('Arial', '', 15);
$pdf->SetTextColor(54, 138, 65); // VERDE JUNTA
$pdf->SetLeftMargin(20);
$pdf->SetY(15);
$html = utf8_decode("EVALUACIÓN CONTINUA DEL ALUMNADO");
$pdf->WriteHTML($html);


$pdf->SetFont('Arial', '', 13);
$pdf->SetTextColor(0, 0, 0); // negro
$pdf->SetLeftMargin(20);
$pdf->SetY(20);
$html = utf8_decode($asignatura);
$pdf->WriteHTML($html);


$pdf->SetFont('Arial', '', 10);

$pdf->SetY(35);
$html = utf8_decode("ALUMNO: " . $alumno);
$pdf->WriteHTML($html);


$pdf->SetLeftMargin(149);
$pdf->SetY(35);
$html = utf8_decode("FECHA: " . date("d/m/Y H:i:s"));
$pdf->WriteHTML($html);


$pdf->SetLeftMargin(20);
$pdf->SetY(50);
$html = utf8_decode("IMAGEN ANTES DE EMPEZAR LA PRÁCTICA");
$pdf->SetTextColor(54, 138, 65); // VERDE JUNTA
$pdf->WriteHTML($html);

$pdf->Image($uploadFile1, 50, 60, 120, 60);



$pdf->SetLeftMargin(20);
$pdf->SetY(130);
$html = utf8_decode("IMAGEN AL FINALIZAR LA PRÁCTICA");
$pdf->SetTextColor(54, 138, 65); // VERDE JUNTA
$pdf->WriteHTML($html);

$pdf->Image($uploadFile2, 50, 140, 120, 60);


$pdf->SetLeftMargin(20);
$pdf->SetY(210);
$html = utf8_decode("¿CÓMO HAS DESARROLLADO LA PRÁCTICA?");
$pdf->SetTextColor(54, 138, 65); // VERDE JUNTA
$pdf->WriteHTML($html);


$pdf->SetLeftMargin(20);
$pdf->SetY(215);
$html = utf8_decode($observaciones);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0); // negro
$pdf->WriteHTML($html);



$pdf->Output('evaluacion.pdf', "I");

?>