<?php
class PptxCalculator
{
	function pptxToText ($arrayOfFileProperties)
    {
    	$pathToFile = $arrayOfFileProperties[1];
        $zip_handle = new ZipArchive();
        $response   = '';
        $response_notes = '';
        if (true === $zip_handle->open($pathToFile)) {
            
            $slide_number = 1; //loop through slide files
            $notes_number = 1; //loop through notesSlide files
            $doc = new DOMDocument();
            $doc_diag = new DOMDocument();
            $doc_notes = new DOMDocument();
            while (($xml_index = $zip_handle->locateName('ppt/slides/slide' . $slide_number . '.xml')) !== false) {
                $xml_data   = $zip_handle->getFromIndex($xml_index);
                $doc->loadXML($xml_data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response  .= strip_tags($doc->saveXML());
                $slide_number++;
                
            }
            if (($xml_index_diag = $zip_handle->locateName('ppt/diagrams/data1.xml')) !== false) {
                $xml_data_diag   = $zip_handle->getFromIndex($xml_index_diag);
                $doc_diag->loadXML($xml_data_diag, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_diag = strip_tags($doc_diag->saveXML());                               
            }
            while (($xml_index_notes = $zip_handle->locateName('ppt/notesSlides/notesSlide' . $notes_number . '.xml')) !== false) {
                $xml_data_notes  = $zip_handle->getFromIndex($xml_index_notes);
                $doc_notes->loadXML($xml_data_notes, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_notes  .= strip_tags($doc_notes->saveXML());
                $notes_number++;
                
            }
            $zip_handle->close();            
        }
        if (!empty($response_diag)) {
            $response = $response_diag . $response;
        }
        if (!empty($response_notes)) {
            $response = $response_notes . $response;
        }        
        $response = preg_replace('/([a-z])([A-Z])|(\d)([A-Z])/s','$1 $2', $response);
        $response = str_ireplace(["/", "-", " - ", ":", ",", ".",";","...", "!","?", "%", '"', "(",")","”", "–","=","$","£", "€","{","}","§","[","]", "…", "“", "#", "<", ">"], [" ", " ", " ", " ", " ", " "," ", " ", " ", " ", " ", " "," ", " "," ", " ", " "," "," "," "," "," ", " ", " ", " ", " ", " ", " ", " ", " "], $response);
        $response = preg_replace('/\s+/', ' ', $response);
        $response = preg_replace('/([A-Z])(\d)/s','$1 $2', $response);
        $response = preg_replace('/([a-z])(\d)/s','$1 $2', $response);
        $response = preg_replace('/(\d)([A-Z])/s','$1 $2', $response);
        $response = preg_replace('/(\d)([a-z])/s','$1 $2', $response);
        $arrayOfWordsAndNumbers = explode(" ", $response);
        $arrayOfNumbersOnly = [];
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
        $finalArrayOfWordsAndNumbers = getFiguresFromString($arrayOfWordsAndNumbers, $arrayOfNumbersOnly);
        $russianTextReplaced = str_ireplace(["а","б","в","г","д","е","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я", "А","Б","В","Г","Д","Е","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я", "_", "visibilitystyle", "visibilitypptxpptystyle", "visibilitypptxppty"], ["a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", "a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", "", "", "", ""], $finalArrayOfWordsAndNumbers[0]);
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
        $numberOfWordsOnlyInAnyText = count($russianTextReplaced);
        $totalNumberOfNumbersOnly = count($finalArrayOfWordsAndNumbers[1]);
        $totalNumberOfWordsAndNumbersInText = $numberOfWordsOnlyInAnyText + $totalNumberOfNumbersOnly;
        function count_repetitions ($array){
            $words = array_count_values($array);
            foreach ($words as $key => $value) {
                if ($value === 1 || $key === ' ') {
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