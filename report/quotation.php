<?php
	require_once('../fpdf.php');
		
	class PDF extends FPDF
	{
		var $widths;
		var $aligns;
		function Header()
		{
			$y = $this->GetY();
			$this->Image('../img/logo_small.png',10,6,20);
			$this->SetY($y);
			$this->Image('../img/trimos_logo.jpg',185,6,20);
			$this->SetFont('Arial', 'B', 15);
			$this->Cell(80);
			$this->Cell(30,10,'TRIMASTER METEROLOGY',0,0,'C');
			$this->SetFont('Arial','',10);
            $this->Cell(-27,20,'202, Vasudha Equinox, Bopodi, Near Khadki railway station, Pune, Maharashtra, India - 411003.',0,0, 'C');
            $this->Cell(27,30,'Tel:  +91 7767014764 / 9823150614 / 8459011480	
            Email:contact@trimaster.in	
            www.trimaster.in',0,0, 'C');
			$this->Ln(20);
		}
		
		function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,5,date("Y-m-d H:m:s"),0,0);
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function ReportTitle($report_title)
		{
			$this->SetFont('Times','B',12);
			$this->Line(10, 27, 210-10, 27);
			$this->SetFillColor(200,220,255);
			$this->Ln(4);
			$this->Cell(0,6,$report_title,0,1,'L',true);
			$this->Ln(4);
		}

		function AddressedTo($company_name,$address,$contact_name, $contact_number)
		{
			$this->SetFont('Arial','',10);
			$y = $this->GetY();
			$this->Cell(0,6,"Quote No. : AMC/2018-19/",0,1,'L');
			$this->Cell(0,6,"Date : ".date('d-m-Y'),0,1,'L');
			$this->Ln(10);
			$this->Cell(0,6,"Your Ref. :",0,1,'L');
			$this->SetY($y);
			$this->SetX(210-70);
			$this->SetFillColor(255,255,255);
			$this->MultiCell(0,6,$company_name,0,'L');
			$this->SetX(210-70);
			$this->MultiCell(0,6,$address,0,'L');
			$this->Ln(5);
			$this->SetX(210-70);
			$this->MultiCell(0,6,"Kind Attn : ".$contact_name,0,'L');
			$this->SetX(210-70);
			$this->MultiCell(0,6,"Contact No: ".$contact_number,0,'L');
		}
		
		function Courtsey()
		{
			$this->Ln();
			$this->SetFont('Arial','',10);
			$this->Cell(0,6,"Dear Sir/Madam,",0,1,'L');
			$this->Cell(0,6,"We are pleased to submit our offer as follows :-",0,1,'L');
		}

		function SetWidths($w)
		{
			//Set the array of column widths
			$this->widths=$w;
		}

		function SetAligns($a)
		{
			//Set the array of column alignments
			$this->aligns=$a;
		}

		function Row($data)
		{
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++)
			{
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				$this->MultiCell($w,5,$data[$i],0,$a);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}

		function CheckPageBreak($h)
		{
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}

		function NbLines($w,$txt)
		{
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
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
		
		function Notif()
		{
			$this->SetFont('Arial','B',10);
			$this->Ln(4);
			$this->MultiCell(0,4,"Note: If any spares replaced/required to be replaced is payable Extra as applicable",0,'L');
			$this->Ln();
		}
		
		function CommercialTerms()
		{
			$this->SetFont('Arial', '', 10);
			$y = $this->GetY();
			$this->Cell(50,6,"Validity", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":30 days from the date of quotation",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"Execution", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":Within 2 weeks on receipt of your PO",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"Payment", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":100% in advance against PI",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"Taxes", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":GST @ 18% payable Extra as applicable",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"To & Fro charges", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":Out station charges are payable extra",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"Lodging & Boarding", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":If over night stay is required, payable extra at actual",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"SAC", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":998717",0,1,'L');
			$y = $this->GetY();
			$this->Cell(50,6,"HSN Code", 0,1,'L');
			$this->SetY($y);
			$this->SetX(50);
			$this->Cell(60,6,":Trimos Spares & Accessories - 9031 9000",0,1,'L');
			$this->SetX(50);
			$this->Cell(60,6,":Trimos Instruments - 9031 8000",0,1,'L');
			$this->Ln(10);
			$y = $this->GetY();
			$this->Rect(10,$y,190,30);
			$y = $this->GetY();
			$this->Cell(50,6,"GST No. 27AABPI3117P1ZN", 0,1,'L');
			$this->Cell(50,6,"TAN No. PNEN10155F",0,1,'L');
			$this->SetY($y);
			$this->SetX(160);
			$this->Cell(60,6,"For Trimaster Metrology",0,1,'L');
			$this->Ln(15);
			$y = $this->GetY();
			$this->Cell(50,6,"PAN No. AABPI3117P", 0,1,'L');
			$this->SetY($y);
			$this->SetX(160);
			$this->Cell(60,6,"Authorized Signatory",0,1,'L');
		}
		
		function Declaration()
		{
			$y = $this->GetY();
			$this->Rect(10,$y,190,50);
			$this->Ln();
			$this->MultiCell(0,6,"We hereby declare that the details given above are correct and complete in all respects to enable an electronic fund transfer through NEFT/RTGS facility. We further accord our consent for receiving payment through NEFT/RTGS mode from our customers.");
			$this->SetX(210-80);
			$this->Cell(0,6,"For Trimater Meterology",0,1,'L');
			$this->Ln(15);
			$this->SetX(210-80);
			$this->Cell(0,6,"Proprietor",0,1,'L');
		}
	}

	$company_name = "Anand Exim";
	$address = "302, Lunkad Solitare, Sadashiv Peth, Pune.";
	$contact_name = "Apoorva Maheshwari";
	$contact_number = "9922049588";
	$data = array(
					array(1,"Option- I", "AMC - Annul Maintenance Contract For your Trimos Vertical Measuring Instrument Model -  (One calibration,Two servicing calls on half yearly basis  & One breakdown call if required during AMC period)",   25000.00, 1, 25000.00),
					array(2,"Option - II", "One Time Servicing & Calibration Charges", 20000.00 , 1, 20000.00 ),
					array(3,"Option- III", "One Time Calibration Charges", 15000.00, 1, 15000.00),
					array(4,"Option- IV", "One Time Servicing Charges", 15000.00, 1, 15000.00)
	);
	$bank = array
			(
				array("Supplier's Name", "Trimaster Meterology"),
				array("Bank's Name", "ICICI Bank"),
				array("Bank's Address", "A, Shangrila Gardens, Bund Garden Road, Pune - 411001"),
				array("Supplier's Account Number", "000505026245"),
				array("Branch", "Pune"),
				array("MICR code of the Branch", "411229002"),
				array("Branch Telephone Number", "020-67574314/4322"),
				array("Branch Code", "0005"),
				array("IFSC Code", "ICIC0000005"),
				array("Account Type", "Current Account"),
				array("Name of the City", "Pune"),
				array("Supplier's Contact Number", "9823147614/9422028396"),
				array("Supplier's email Id", "natarajan@trimaster.in/ meena@trimaster.in")
			);
	$w= array(15,20,70,30,25,30);
	$w2 = array(100,90);
	$header= array("Sr. No.", "Article No.", "Description", "Unit Price", "Qty", "Amount");
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage('P');
	$pdf->SetFont('Times','',12);
	$pdf->ReportTitle("Service Quotation");
	$pdf->AddressedTo($company_name, $address, $contact_name, $contact_number);
	$pdf->Courtsey();
	$pdf->SetWidths($w);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Row($header);
	$pdf->SetFont('Arial', '', 10);
	foreach($data as $row)
		$pdf->Row($row);
	$pdf->Notif();
	$pdf->CommercialTerms();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	$pdf->ReportTitle("NEFT/RTGS Details");
	$pdf->SetWidths($w2);
	$pdf->SetFont('Arial','',10);
	foreach($bank as $row)
		$pdf->Row($row);
	$pdf->Declaration();
	$pdf->Output();
?>
			
