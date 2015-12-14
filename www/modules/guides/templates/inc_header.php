<html>
<head>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<title>Velotour admin: </title>
<LINK href="/index.css" type="text/css" rel="stylesheet">
<LINK href="/admin/admin.css" type="text/css" rel="stylesheet">
<LINK href="/libs/css/jquery.rte.css" type="text/css" rel="stylesheet">
<LINK href="/libs/css/jquery-ui.css" type="text/css" rel="stylesheet">
<script src="/admin/jquery/jquery.js"></script>
<script src="/admin/jquery/jquery-ui.js"></script>
<script src="/libs/js/jquery.rte.tb.js"></script>
<script src="/libs/js/jquery.rte.js"></script>

<link rel="stylesheet" href="/js/jquery.sel_filter.css" type="text/css" />


<script type="text/javascript" src="/js/chosen/chosen.jquery.js"></script>
<LINK href="/js/chosen/chosen.css" type="text/css" rel="stylesheet">

<script type="text/javascript" src="/js/jquery.multiselect.js"></script>
<script type="text/javascript" src="/js/jquery.sel_filter.js"></script>
<script type="text/javascript" src="/js/jquery.form.interaction.js"></script>
<script type="text/javascript" src="/js/jquery.preview.js"></script>
<script type="text/javascript" src="/js/jquery.sortElements.js"></script>
<script type="text/javascript" src="/js/functions.js"></script>

</head>
<body>


{literal}
<script language="JavaScript">
function hide(el) {
el_obj = document.getElementById(el)
el_obj.style.display = 'none'; 
return false;}
function show(el) {
	el_obj = document.getElementById(el)
	el_obj.style.display = 'block';
	return false;
}
function showhide(el) {
	el_obj = document.getElementById(el);
	if (el_obj.style.display == 'none') {
			show(el);
	} else {
			hide(el);
	}
	return false;
}

$(document).ready(function(){
	$(".fSelect").sel_filter();
	$(".lOne2One").sel_filter();
	$(".chosen").chosen();
	$(".fDateTime").datepicker({ dateFormat: "yy-mm-dd",  showAnim: ""  });
});
</script>
{/literal}

