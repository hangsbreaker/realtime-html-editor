<?php
if(isset($_POST['data'])){
	$str=$_POST['data'];
	eval("?> $str <?php ");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html">
<title>HTML Editor</title>
<script src="dist/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script type="text/javascript">
var editboxHTML ='<html class="expand close">'+
'<script src="pace/js/pace.min.js"><\/script>'+
'<link href="pace/themes/minimal.css" rel="stylesheet" \/>'+
'<head><style type="text/css">#editor{position:absolute;top:0;bottom:0;left:0;right:0;width:100%;}<\/style><\/head>'+
'<body><div id="editor"><\/div>'+
'<script src="src-noconflict/ace.js"><\/script>'+
'<script src="src-noconflict/ext-language_tools.js"><\/script>'+
'<script>'+
'ace.require("ace/ext/language_tools");'+
'var editor = ace.edit("editor");'+
'editor.session.setMode("ace/mode/html");'+
'editor.setTheme("ace/theme/monokai");'+
'editor.getSession().setUseWrapMode(true);'+
'editor.setOptions({enableBasicAutocompletion: true,enableSnippets: true});'+
'function models(){var model = document.getElementById("model").value;editor.session.setMode("ace/mode/"+model);}'+
'<\/script><\/body><\/html>';

var defaultStuff = "<script src=\"dist\/js\/jquery-1.8.3.min.js\" type=\"text\/javascript\"><\/script>\n<script>\ndocument.write('Write HTML in left');\n<\/script>";
var old = "";
function init(){
	window.editbox.document.open();
	window.editbox.document.write(editboxHTML);
	window.editbox.document.close();
	update();
}
function update(){
	if (window.editbox.document.readyState == "interactive") {
		window.editbox.editor.getSession().setValue(defaultStuff);
	}else if (window.editbox.document.readyState == "complete"){
		var editext = window.editbox.editor.getSession().getValue();
		var d = dynamicframe.document;
		if (old != editext){
			old = editext;
			$.post(window.location.pathname,{data:editext},function(data){
				if(data.includes("Parse error") || data.includes("Warning")){
					var err=data.split(" in ");
					data=err[0];
				}
				d.open();
				d.write(data);
				d.close();
			});
		}
	}
	//console.log(window.editbox.document.readyState);
	window.setTimeout(update, 150);
}
window.onbeforeunload = function(e) {
    return true;
};
</script>
</head>
<frameset onLoad="init();" resizable="yes" cols="35%,*" border="5" bordercolor="#777">
  <!-- about:blank confuses opera, so use javascript: URLs instead -->
  <frame name="editbox" src="javascript:'';">
  <frame name="dynamicframe" src="javascript:'';">
</frameset><noframes></noframes>
</html>
