<!DOCTYPE html>
<html>
<head>
	<title>Get and copy your quote!</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel='stylesheet' href='styles.css'>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div class='results'>
Name of your file: <font color='#17a2b8' size='6'><?php echo $inputFileName; ?></font><br /><br />

Words <u>AND</u> numbers in your file:<br> <textarea readonly class='resizedTextbox inlined' id='words_and_numbers1'><?php echo $resultingNiceArray[0]; ?></textarea> <button class='simple_copy inlined btn btn-secondary' id='copy_button1'>Copy</button><br />
		Words in your file (<u>WITHOUT</u> numbers):<br> <textarea readonly class='resizedTextbox inlined' id='words_and_numbers2'><?php echo $resultingNiceArray[1]; ?></textarea> <button class='simple_copy inlined btn btn-secondary' id='copy_button2'>Copy</button><br />
		Numbers in your file:<br> <textarea readonly class='resizedTextbox inlined' id='words_and_numbers3'><?php echo $resultingNiceArray[2]; ?></textarea> <button class='simple_copy inlined btn btn-secondary' id='copy_button3'>Copy</button><br /><br />
		Repetitions in your file (among words only):<br> <textarea readonly class='resizedTextbox inlined' id='words_and_numbers4'><?php echo $resultingNiceArray[3]; ?></textarea> <button class='simple_copy inlined btn btn-secondary' id='copy_button4'>Copy</button><br />
		% of repetitions:<br> <textarea readonly  class='resizedTextbox inlined' id='words_and_numbers5'><?php echo $resultingNiceArray[4]; ?></textarea> <button class='simple_copy inlined btn btn-secondary' id='copy_button5'>Copy</button><br/><br/>Enter your full rate: <textarea class='resizedTextbox inlined moved_down' id='your_rate' maxlength='5'>0.00</textarea><br/>Enter your rate <u>for repetitions</u>: <textarea class='resizedTextbox inlined moved_down' id='rep_rate' maxlength='5'>0.00</textarea><br /><br /><div class='returned_results'><button class="btn btn-secondary" id='copy_button6'>Get and copy your quote!</button><textarea readonly  class='resizedTextboxQuote' id='quote'>0.00</textarea><div class='support_text'><div class="back_button btn btn-info" onclick="location.href='https://t9ncount.com'">Back to counter</div><br><br>Would you like to <u>support</u> t9ncounter.com?<br>Drop me a line to <a href="mailto:info@engrutra.com">Vadim Kadyrov</a>!</div></div>
<script type="text/javascript" src="scripts.js"></script>
</body>
</html>