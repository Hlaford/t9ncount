//'words and numbers in your file' field
var input1  = document.getElementById('words_and_numbers1');
var button1 = document.getElementById('copy_button1');
button1.addEventListener('click', function (event) {
	event.preventDefault();
	input1.select();
	document.execCommand('copy');
});

//'words in your file (without numbers)' field
var input2  = document.getElementById('words_and_numbers2');
var button2 = document.getElementById('copy_button2');
button2.addEventListener('click', function (event) {
    event.preventDefault();
    input2.select();
    document.execCommand('copy');
});

//'numbers in your file' field
var input3  = document.getElementById('words_and_numbers3');
var button3 = document.getElementById('copy_button3');
button3.addEventListener('click', function (event) {
	event.preventDefault();
	input3.select();
	document.execCommand('copy');
});

//'Repetitions in your file (among words only)' field
var input4  = document.getElementById('words_and_numbers4');
var button4 = document.getElementById('copy_button4');
button4.addEventListener('click', function (event) {
	event.preventDefault();
	input4.select();
	document.execCommand('copy');
});

//'% of repetitions' field
var input5  = document.getElementById('words_and_numbers5');
var button5 = document.getElementById('copy_button5');
button5.addEventListener('click', function (event) {
	event.preventDefault();
	input5.select();
	document.execCommand('copy');
});

//'Get and copy your quote!' field
var input7  = document.getElementById('quote');
    var numberOfWordsAndNumbers = document.getElementById('words_and_numbers1').value;
var result = 0;
var numberOfReps = document.getElementById('words_and_numbers4').value
var getQuoteButton = document.getElementById('copy_button6');
getQuoteButton.addEventListener('click', function (event) {
    event.preventDefault();
	var rateForWord = document.getElementById('your_rate').value;
	var ratForRepetitions = document.getElementById('rep_rate').value
	if (ratForRepetitions === '0.00' || ratForRepetitions === '' || ratForRepetitions === '0.0' || ratForRepetitions === '0' || ratForRepetitions === '0.' || ratForRepetitions === '0.000') {
		var result = numberOfWordsAndNumbers * rateForWord;
	} else {
		var result = (numberOfWordsAndNumbers - numberOfReps) * rateForWord + (numberOfReps * ratForRepetitions);
	}
	if (isNaN(result)) {
	alert ('Enter valid numbers for rates!');
	
	} else {
	document.getElementById('quote').value = Math.round(result * 100) / 100;
	input7.select();
        document.execCommand('copy');
	}
});