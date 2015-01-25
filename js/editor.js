
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

function replacePattern(obj, id) {
	/*rekurzivně projít DOM "obj"ektu a prohledávat atributy:
		id, name, onClick
		pokud obsahují podřetězec NEXT_ID, nahradí se číslem id (2. param) 	
	*/

	//TODO	
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
	cpy.insertBefore(orig);
	replacePattern(orig, getNextId());
	orig.css("display", "block");
}
