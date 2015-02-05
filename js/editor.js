
function editorQTypChange(val) {
	divs = $("#content div.qType");
	hide = divs.not("#qType-" + val);
	show = divs.filter("#qType-" + val);
	hide.css("display", "none");
	show.css("display", "block");
}

function editorATypChange(id, val) {
	divs = $("#content div#odpoved-"+id+" div.aType");
	hide = divs.not(".typ-" + val);
	show = divs.filter(".typ-" + val);
	hide.css("display", "none");
	show.css("display", "block");
}

function editorDeleteA(id) {
	//convert "blabla-1" to "1"
	if (isNaN(id))
		id = id.substring(id.indexOf("-")+1); 
	
	$("#content input#delete-"+id).val(1);
	$("#content div#odpoved-"+id).hide('slow');	
}


function getNextId() {
	var nextIdObj = $('input[name=nextId]');
	var id = parseInt(nextIdObj.val());
	nextIdObj.val(id+1);
	return id;	
}

function editorAddA() {
	var orig = $("#content #odpoved-NEXT_ID");
	var cpy = orig.clone(true);
	cpy.insertAfter(orig);

	var newId = getNextId();
	var idStr = orig.attr('id').replace("NEXT_ID", newId);
	orig.attr('id', idStr);	

	$("#content #"+idStr+" [id*='NEXT_ID']").each(function() { this.id = this.id.replace("NEXT_ID", newId); });
	$("#content #"+idStr+" [name*='NEXT_ID']").each(function() { this.name = this.name.replace("NEXT_ID", newId); });
	//nelze - onclick není string, ale funkce
	//$("#content #"+idStr+" [onclick*='NEXT_ID']").map(function() { this.onclick = this.onclick.replace("NEXT_ID", newId); });
		
	orig.css("display", "block");
}

function deMathQuillize() {
	$("[id*='mathQuillizedInput']").each(function() {
		$("#deMathQuillizedInput" + this.id.substr("mathQuillizedInput".length)).val($(this).mathquill('latex'));
	});
}
