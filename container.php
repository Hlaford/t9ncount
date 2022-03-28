<?php
// auxiliary function to nicely visualise debugging process:
// function pr($data)
// {
//     echo "<pre>";
//     echo "<h1>";
//     var_dump($data);
//     echo "</h1>";
//     echo "</pre>";
// }
$arrayOfFileProps = array($_FILES["zip_file"]["type"], $_FILES["zip_file"]["tmp_name"], $_FILES["zip_file"]["name"]);
try {
	if (isset($arrayOfFileProps[1])) {
		$okay = false;
		$inputFileName = $arrayOfFileProps[2];
		$type = $arrayOfFileProps[0];
		$accepted_extensions = array('xlsx', 'xls','csv', 'docx', 'pptx', 'pdf', 'ods');
		$accepted_types = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/pdf', 'application/vnd.oasis.opendocument.spreadsheet');
		$name = explode (".", $inputFileName);
		if (count($name) == 1 && !isset($name[1])) {
			throw new Exception ("Check the extension of your file, or upload another file");
			exit;
		}
		foreach ($accepted_extensions as $extension_ok) 
		{
			if (strtolower($name[count($name)-1]) == $extension_ok) {
				$okay = true;
				break;
			} else {
				continue;
			}
		}
		if ($okay == false) {
			throw new Exception ("The file extension you are trying to upload is not supported. Please upload another file");
			exit;
		} else if ($okay == true) {
			foreach($accepted_types as $mime_type) {
				if($type == $mime_type) {
					$okay = true;
					break;
				} else {
					continue;
				}
					}
		}
		if ($okay == false) {	
			throw new Exception ("The file type you are trying to upload is not supported. Please upload another file");
				exit;
		}			
	}
} catch (Exception $e) {
	exit ("<h1>" . $e->getMessage() . "</h1>");
} 
if (strtolower($name[count($name)-1]) === 'xlsx' || strtolower($name[count($name)-1]) === 'xls' || strtolower($name[count($name)-1]) === 'ods' || strtolower($name[count($name)-1]) === 'csv') {
	require_once 'counter_of_excel.php';
	$lab = new ExcelCalculator;
	$method_array = get_class_methods('ExcelCalculator');
	$last_position = count($method_array) - 1;
	try {
		$method_name = $method_array[$last_position];
		$result = $lab->$method_name($arrayOfFileProps);
	} catch (Exception $e) {
		exit ($e->getMessage() . "<h1>" . "Upload another file!" . "</h1>");
	}
} else if (strtolower($name[count($name)-1]) === 'docx') {
	require_once 'counter_of_docx.php';
	$dab = new DocxCalculator;
	$method_array = get_class_methods('DocxCalculator');
	$last_position = count($method_array) - 1;
	try {
		$method_name = $method_array[$last_position];
		$result = $dab->$method_name($arrayOfFileProps);
	} catch (Exception $e) {
		exit ($e->getMessage() . "<h1>" . "Upload another file!" . "</h1>");
	}
} else if (strtolower($name[count($name)-1]) === 'pptx') {
	require_once 'counter_of_pptx.php';
	$dab = new PptxCalculator;
	$method_array = get_class_methods('PptxCalculator');
	$last_position = count($method_array) - 1;
	try {
		$method_name = $method_array[$last_position];
		$result = $dab->$method_name($arrayOfFileProps);
	} catch (Exception $e) {
		exit ($e->getMessage() . "<h1>" . "Upload another file!" . "</h1>");
	}
} else if (strtolower($name[count($name)-1]) === 'pdf') {
	require_once 'counter_of_pdf.php';
	$smab = new PdfCalculator;
	$method_array = get_class_methods('PdfCalculator');
	$last_position = count($method_array) - 1;
	try {
		$method_name = $method_array[$last_position];
		$result = $smab->$method_name($arrayOfFileProps);
	} catch (Exception $e) {
		exit ($e->getMessage() . "<h1>" . "Upload another file!" . "</h1>");
	}
}
else {
	exit ("<h1>Bad file format, upload another file.<br /> If you haven`t uploaded anything yet, go to the <a href='https://t9ncounter.com'>home page</a> and upload a file.</h1>"); 
}
if (isset($result)) {
	if (is_array($result)) {
		//debugging function above:
		//pr($result);
		$resultingNiceArray = $result;
		include "output.html.php";		
	} else {
		print "<h1>" . $result . " Re-upload your file, or upload another file!</h1>";
	}
}
?>