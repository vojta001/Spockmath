function hideFlmError(){
	$("#decimalError").remove();
}

function showFlmError(){
	if (!$("#decimalError").length){
		$("#flash-msg").append($('<div id="decimalError" class="error">Odpověď musí být číslo!</div>'));
	}
}

function isValidDecimal(toValidate) {
	var valid = true;
	toValidate.each(function(i) {
		str = this.value.replace(',', '.');
		if (str.length && isNaN(str)) {
			valid = false;
		}
	});
	return valid;
}

function validateEdit(obj){
	if (isValidDecimal($(obj))) {
		$(obj).removeClass('error');
		if (isValidDecimal($('.decimalTextBox')))
			hideFlmError();
	} else {
		$(obj).addClass('error');
		showFlmError();
	}
}


$('document').ready(function() {
	$('.decimalTextBox').keyup(function() {
		validateEdit(this);
	});
});

