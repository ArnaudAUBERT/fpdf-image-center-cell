<?php
/*
 * PDPF Image Centered in a cell: conserve aspect ratio
 * Arnaud AUBERT - 2015-01-03 
 * http://www.arnaudaubert.fr/
 * Licence: Apache 2.0
*/

require("fpdf/fpdf.php");

class PDF extends FPDF 
{

	function imageCenterCell($file, $x, $y, $w, $h)
	{
		if (!file_exists($file)) 
		{
			$this->Error('File does not exist: '.$file);
		}
		else
		{
			list($width, $height) = getimagesize($file);
			$ratio=$width/$height;
			$zoneRatio=$w/$h;

			// Same Ratio, put the image in the cell
			if ($ratio==$zoneRatio)
			{
				$this->Image($file, $x, $y, $w, $h);
			}

			// Image is vertical and cell is horizontal
			if ($ratio<$zoneRatio)
			{
				$neww=$h*$ratio; 
				$newx=$x+(($w-$neww)/2);
				$this->Image($file, $newx, $y, $neww);
			}

			// Image is horizontal and cell is vertical
			if ($ratio>$zoneRatio)
			{
				$newh=$w/$ratio; 
				$newy=$y+(($h-$newh)/2);
				$this->Image($file, $x, $newy, $w);
			}
		}
	}

}

// usage:
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFillColor(0,0,0);

// Print a horizontal image in a vertical cell
$pdf->Rect(10,10,40,50,'F');
$pdf->imageCenterCell("test.jpg",10,10,40,50);

// Print a vertical image in a horizontal cell
$pdf->Rect(80,10,100,50,'F');
$pdf->imageCenterCell("test2.jpg",80,10,100,50);

// Print a square image
$pdf->imageCenterCell("test3.jpg",10,70,100,100);

$pdf->Output('I');

?>
