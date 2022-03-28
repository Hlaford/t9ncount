<?php
class DocxCalculator
{
	function docxToText ($arrayOfFileProperties)
    {
    	$pathToFile = $arrayOfFileProperties[1];
        $response = '';
        $zip = zip_open($pathToFile);
        if (!$zip || is_numeric($zip)) {
            return false;
        }
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE)
                continue;
            if (zip_entry_name($zip_entry) != 'word/document.xml')
                continue;
            $response .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }
        zip_close($zip);
        $response = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $response);
        $response = str_replace('</w:r></w:p>', "\r\n", $response);
        $response = strip_tags($response);
        $footer_number = 1;
        $zip_handle = new ZipArchive();
        $response_footer   = '';
        $doc_footer = new DOMDocument();
        if (true === $zip_handle->open($pathToFile)) {
            while (($xml_index_footer = $zip_handle->locateName('word/footer' . $footer_number . '.xml')) !== false) {
                $xml_data_footer = $zip_handle->getFromIndex($xml_index_footer);
                $doc_footer->loadXML($xml_data_footer, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_footer  .= strip_tags($doc_footer->saveXML());
                $footer_number++;
                
            }            
        } 
        if (!empty($response_footer)) {
            $response = $response_footer . $response;
        }
        $header_number = 1;
        $zip_handle = new ZipArchive();
        $response_header   = '';
        $doc_header = new DOMDocument();
        if (true === $zip_handle->open($pathToFile)) {
            while (($xml_index_header = $zip_handle->locateName('word/header' . $header_number . '.xml')) !== false) {
                $xml_data_header = $zip_handle->getFromIndex($xml_index_header);
                $doc_header->loadXML($xml_data_header, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_header  .= strip_tags($doc_header->saveXML());
                $header_number++;
                
            }            
        } 
        if (!empty($response_header)) {
            $response = $response_header . $response;
        }
        $custom_number = 1;
        $zip_handle = new ZipArchive();
        $response_custom   = '';
        $doc_custom = new DOMDocument();
        if (true === $zip_handle->open($pathToFile)) {
            while (($xml_index_custom = $zip_handle->locateName('customXml/item' . $custom_number . '.xml')) !== false) {
                $xml_data_custom = $zip_handle->getFromIndex($xml_index_custom);
                $doc_custom->loadXML($xml_data_custom, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_custom .= strip_tags($doc_custom->saveXML());
                $custom_number++;
                
            }            
        } 
        if (!empty($response_custom)) {
            $response = $response_custom . $response;
        }
        $zip_handle = new ZipArchive();
        $response_footnotes = '';
        $doc_footnotes = new DOMDocument();
        if (true === $zip_handle->open($pathToFile)) {
            if (($xml_index_footnotes = $zip_handle->locateName('word/footnotes.xml')) !== false) {
                $xml_data_footnotes = $zip_handle->getFromIndex($xml_index_footnotes);
                $doc_footnotes->loadXML($xml_data_footnotes, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response_footnotes = strip_tags($doc_footnotes->saveXML());                
            }            
        } 
        if (!empty($response_custom)) {
            $response = $response_footnotes . $response;
        }
        $response = str_ireplace(["/", "-", " - ", ":", ",", ".",";","...", "!","?", "%", '"', "(",")","”", "–","=","$","£", "€","{","}","§","[","]"], [" ", " ", " ", " ", " ", " "," ", " ", " ", " ", " ", " "," ", " "," ", " ", " "," "," "," "," "," ", " ", " ", " "], $response);
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
        $russianTextReplaced = str_ireplace(["а","б","в","г","д","е","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я", "А","Б","В","Г","Д","Е","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я", "_"], ["a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", "a","b","v","g","d","ye","zh","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","tsh","tv","y","mz","e","yu","ya", ""], $finalArrayOfWordsAndNumbers[0]);
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