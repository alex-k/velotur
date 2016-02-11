$(document).ready (function () {
	$("form").interaction({startfield:'Make_id',start_pattern:'.lOne2One,.interact_start_field [name]'});
	$(".lMany2Many").gs_multiselect();
	$(".fMultiSelect").gs_multiselect();
	$(".chosen_multiselect").chosen();
	$(".chosen").chosen();
	$(".lOne2One").sel_filter( {slide_width: 150, min_options: 1, crop: false});
	$(".fSelect").sel_filter();
	$(".fDateTime").datepicker();

	$(".sortable").sortable();

	$('.fWysiwyg').rte( {
		//css: ['default.css'],
controls_rte: rte_toolbar,
controls_html: html_toolbar
	});

	$("[-data-href]").dblclick(function() {
		window.document.location.href=$(this).attr('-data-href');
		return false;
	});
	$("[-data-href]").each(function() {
		$(this).attr('title',$(this).attr('-data-href'));
	});

	$("table.tb").children("tbody").children("tr").mouseover(function() {
		$(this).addClass("over");
	});

	$("table.tb tr").mouseout(function() {
		$(this).removeClass("over");
	});

	$('#tpl_content').each(function () {
		//window['tpl_codemirror'] = CodeMirror.fromTextArea(this, { mode:"text/html", tabMode:"indent",lineNumbers: true });

		window['tpl_codemirror'] = CodeMirror.fromTextArea(this,
		{
lineNumbers: true,
matchBrackets: true,
mode: "application/x-httpd-php",
			indentUnit: 8,
indentWithTabs: true,
enterMode: "keep",
tabMode: "shift"
		});
	});

	$('.ch_all').click(
	function() {
		$('.ch1').attr('checked',this.checked);
	}
	);
	$('.fDateTimeFilter').each(function() {
		$(this).daterangepicker(
		{
dateFormat: $.datepicker.ATOM,
onOpen: function() {
				$('.ui-daterangepicker:visible .ui-daterangepicker-specificDate').trigger('click');
			}
		}
		);
	});

	$('.form_help_over').mouseover(function() {
		var spoiler=$(this).closest('.form_help_container').find('.form_help');
		spoiler.delay(1500).show();
	});
	$('.form_help_over').mouseout(function() {
		var spoiler=$(this).closest('.form_help_container').find('.form_help');
		spoiler.hide();
	});

	
	$('.admin_img_preview').preview();

	

});

function md(obj) {
	var str="";
	for (key in obj) {
		str +="\n"+key+"="+obj[key];
	}
	return str;
}

