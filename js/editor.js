
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
	$("#content input#delete-"+id).val(1);
	$("#content div#odpoved-"+id).hide('slow');	
}

function replaceNext(obj) {

}

function editorAddA() {
	var orig = $("#content #odpoved-NEXT_ID");
	var copy = orig.clone(true);
	//orig.id = 17;
	copy.appendTo(orig.parent());
}
