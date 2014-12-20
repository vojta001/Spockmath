
function editorQTypChange(val) {
	divs = $("#content div.qType");
	hide = divs.not("#"+val);
	show = divs.filter("#"+val);
	hide.css("display","none");
	show.css("display","block");
}