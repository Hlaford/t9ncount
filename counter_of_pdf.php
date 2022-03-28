<?php
require __DIR__ . '/vendor/autoload.php';
class PdfCalculator 
{
	function calculateNumberOfWords($arrayOfFileProperties) 
	{					
		$inputFileName = $arrayOfFileProperties[1];
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($inputFileName);
		$text = $pdf->getText();	
		$text = str_ireplace(["\\f", "\\b","\\", "/", " - ", ":", ",", ".",";","...", "!","?", "%", '"', "(",")","”", "–","=","$","£", "€","{","}","§","[","]", "…", "“", "#", "<", ">"], ["", "","", " "," ", " ", " ", " "," ", " ", " ", " ", " ", " "," ", " "," ", " ", " "," "," "," "," "," ", " ", " ", " ", " ", " ", " ", " ", " "], $text);
		$oneDimensionArrayOfStrings = preg_split( "/(\s+)/", $text );
		function mergeWords ($array) {
			foreach ($array as $key => $value) {
				$arrayedString = str_split($value);
				if ($arrayedString[count($arrayedString)-1] === "-") {
					$array[$key] = $array[$key] . $array[$key+1];
					unset($array[$key+1]);
				}				
			}
			return $array;
		}
		$oneDimensionArrayOfStrings = mergeWords($oneDimensionArrayOfStrings);
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
		$russianTextReplaced = str_ireplace(["-","а","б","в","г","д","е","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я", "А","Б","В","Г","Д","Е","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я", "_"], ["","a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", "a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", ""], $arrayOfWordsAndNumbers[0]);
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
			$percentageOfRepetitions = ($wordRepetitionsCount * 100)/count ($russianTextReplaced);
			$realWordRepetitionsCount = ($numberOfWordsOnlyInAnyText / 100) * $percentageOfRepetitions;
			return $arrayOfFinalFigures = array($totalNumberOfWordsAndNumbersInText, $numberOfWordsOnlyInAnyText, $totalNumberOfNumbersOnly, round($realWordRepetitionsCount), round($percentageOfRepetitions));
		} else {
			return "Your file is empty.";
		}
	}
}
?>