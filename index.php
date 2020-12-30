<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'inc/tcpdf/tcpdf.php';
include 'inc/tcpdi/tcpdi.php';

$pdf = new WaterWoo_TCPDI();

$data = array(
	'text' => 'Ce document est la propriété',
	'first_name' => strtoupper('sanjay'),
	'last_name' => strtoupper('chaudhary'),
	'email' => 'sanjayshuuya@gmail.com',
);
$str =$data['last_name'].' '.$data['first_name'].' ('.$data['email'].')';
$pageCount = $pdf->setSourceFile('test.pdf');	

// iterate through all pages
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
	// import a page
	$templateId = $pdf->importPage($pageNo);
	// get the size of the imported page
	$size = $pdf->getTemplateSize($templateId);
	// create a page (landscape or portrait depending on the imported page size)
	if ($size['w'] > $size['h']) {
	    $pdf->AddPage('L', array($size['w'], $size['h']));

	} else {
	    $pdf->AddPage('P', array($size['w'], $size['h']));
	}
	// use the imported page
	$pdf->useTemplate($templateId);
	$pdf->SetFont('Helvetica');
	$pdf->SetTextColor(255,255,255);
	$pdf->SetXY(0,180);
	$pdf->StartTransform();
	$pdf->Rotate(90);
	$pdf->Write(8,$str);
	$pdf->StopTransform();
	// $pdf->SetXY(2, -5);
	// $pdf->RotatedText(5,250,"Example of rotated text",90);
	
}

$file  = "/var/www/html/sc/myfile.pdf";
// Output the new PDF
$pdf->Output($file, 'F' );


/*
* Add your own functions here. You can also copy some of the theme functions into this file. 
* Wordpress will use those functions instead of the original functions then.
*/

/*
* PDF Stamper includes
*/ 

include 'inc/tcpdf/tcpdf.php';
include 'inc/tcpdi/tcpdi.php';


// define the edd_requested_file callback 
function filter_edd_requested_file( $requested_file, $download_files, $args_file_key ) { 

    $upload_dir = wp_upload_dir();    
    $extension = edd_get_file_extension( $requested_file );
    $file_name = $download_files['1']['name'];
    $pdf = new WaterWoo_TCPDI();
    $user_data = edd_get_purchase_session();
    $data = array(
        'first_name' => strtoupper($user_data['user_info']['first_name']),
        'last_name' => strtoupper($user_data['user_info']['last_name']),
        'email' => $user_data['user_info']['email'],
    );
    $str =$data['last_name'].' '.$data['first_name'].' ('.$data['email'].')';
    $pageCount = $pdf->setSourceFile($requested_file);	
    // // iterate through all pages
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
	    // import a page
	    $templateId = $pdf->importPage($pageNo);
	    // get the size of the imported page
	    $size = $pdf->getTemplateSize($templateId);
	    // create a page (landscape or portrait depending on the imported page size)
	    if ($size['w'] > $size['h']) {
	        $pdf->AddPage('L', array($size['w'], $size['h']));

	    } else {
    	    $pdf->AddPage('P', array($size['w'], $size['h']));
	    }
	    // use the imported page
        $pdf->useTemplate($templateId);
        $pdf->SetFont('Helvetica');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(0,180);
        $pdf->StartTransform();
        $pdf->Rotate(90);
        $pdf->Write(8,$str);
        $pdf->StopTransform();
    }
    $file  = $upload_dir['basedir']."/stamper".$file_name.'.'.$extension;
    $pdf->Output($file, 'F' );
    return $file; 
}; 
// add the filter 
add_filter( 'edd_requested_file', 'filter_edd_requested_file', 10, 3 ); 


