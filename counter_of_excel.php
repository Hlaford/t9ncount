<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
require __DIR__ . '/vendor/autoload.php';
class ExcelCalculator 
{
	function calculateNumberOfWords($arrayOfFileProperties) 
	{					
		$inputFileName = $arrayOfFileProperties[2];
		$name = explode (".", $inputFileName);
		$firstCapitalLetter = ucfirst($name[count($name)-1]);
		$reader = IOFactory::createReader($firstCapitalLetter);
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($arrayOfFileProperties[1]);
		$arrayOfWorksheets = $spreadsheet->getAllSheets();
		$counter = 0;
		$arrayOfResultingArrays = [];
		while ($counter < count($arrayOfWorksheets))
		{
			array_push ($arrayOfResultingArrays, $arrayOfWorksheets[$counter]->toArray(null, true, true, true));
			$counter++;
		}
		function filterData($array)
		{
			foreach ($array as $key => &$value) {
        		if (is_array($value))
            		$value = filterData($value);

        		if (!is_array($value) && !is_string($value) && !is_numeric($value)) {
        			unset($array[$key]);
        		}
    		}

    	return $array;
		}
		$protoArrayOfStringsAndFloats = filterData($arrayOfResultingArrays);
		function array_flatten($array) 
		{ 
			if (!is_array($array)) { 
		    	return FALSE; 
		  	} 
		  	$result = array(); 
		  	foreach ($array as $key => $value) { 
		    	if (is_array($value)) { 
		      		$arrayList=array_flatten($value);
		      		foreach ($arrayList as $listItem) {
		        		$result[] = $listItem; 
		      		}
		    	} else { 
		    		$result[$key] = $value; 
		   		} 
		  	} 
		 	return $result; 
		} 
		$protoArrayOfStringsAndFloats = implode(" ", array_flatten($protoArrayOfStringsAndFloats));
		$protoArrayOfStringsAndFloats = str_ireplace(["/", "-", " - ", ":", ",", ".",";","...", "!","?", "%", '"', "(",")","”", "–","=","$","£", "€","{","}","§","[","]"], [" ", " ", " ", " ", " ", " "," ", " ", " ", " ", " ", " "," ", " "," ", " ", " "," "," "," "," "," ", " ", " ", " "], $protoArrayOfStringsAndFloats);
		$oneDimensionArrayOfStrings = explode(" ", $protoArrayOfStringsAndFloats);
		$arrayOfIsNumericStrings = [];
		function getFiguresFromString ($array, $resultingArray)
		{
			foreach ($array as $key => $value) {
				if(is_numeric($value)){
					array_push($resultingArray, $value);
					unset($array[$key]);
				}
			}
			return [$array, $resultingArray];
		}
		$arrayOfWordsAndNumbers =  getFiguresFromString ($oneDimensionArrayOfStrings, $arrayOfIsNumericStrings);
		$russianTextReplaced = str_ireplace(["а","б","в","г","д","е","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я", "А","Б","В","Г","Д","Е","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я", "_"], ["a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", "a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", ""], $arrayOfWordsAndNumbers[0]);
		function removeEmptyWords ($array)
		{
			foreach ($array as $key => $value) {
				if ($value === "" || $value === "-") {
					unset ($array[$key]);
				}
			}
			return $array;
		}
		$russianTextReplaced = removeEmptyWords($russianTextReplaced);
		$numberOfWordsOnlyInAnyText = str_word_count(implode(" ", $russianTextReplaced), 0);
		$totalNumberOfNumbersOnly = count($arrayOfWordsAndNumbers[1]);
		$totalNumberOfWordsAndNumbersInText = $numberOfWordsOnlyInAnyText + $totalNumberOfNumbersOnly;
		function count_repetitions ($array){
			$words = array_count_values($array);
			foreach ($words as $key => $value) {
				if ($value === 1 || $key === "") {
					$words[$key] = 0;
				} else {
					$words[$key] = $words[$key] - 1;
				}
			}
			return $words;
		}
		$wordRepetitionsCount = array_sum(count_repetitions (explode(" ", implode(" ", $russianTextReplaced))));
		if ($numberOfWordsOnlyInAnyText !== 0) {
			$percentageOfRepetitions = ($wordRepetitionsCount * 100)/$numberOfWordsOnlyInAnyText;
			return $arrayOfFinalFigures = array($totalNumberOfWordsAndNumbersInText, $numberOfWordsOnlyInAnyText, $totalNumberOfNumbersOnly, $wordRepetitionsCount, round($percentageOfRepetitions));
		} else {
			return "Your file is empty.";
		}
	}
}
?>