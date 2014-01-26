/**
 * Pour la fenêtre de dialog quand un item est acheté
 */
function dialogAddCommentIssue()
{
	$("#dialogAddCommentIssue").dialog({
		autoOpen: false,
		height: 200,
		width: 550,
		modal: true,
		buttons:
		{
			"Envoyer": function()
			{
				var number = $("#AddCommentIssueNumber").val();
				var text = $("#textAddCommentIssue").val();
				
				$.ajax({
					url: base_url+"/issues/bugComment",
					data: {number: number, text: text},
					type: 'post'
				})
				.done(function(data) {
					$("#dialogAddCommentIssue").dialog("close");
					//page($(".menuSelected"), $(".menuSelected").attr("id"));
				})
				.fail(function() {alert("Désolé j'ai crashé");});
			},
			"Annuler": function() {$(this).dialog("close");}
		},
		close: function() {},
		open: function(event) {
			console.log(event);
		}
	});
	
	$(".ui-dialog-titlebar").removeClass("ui-corner-all");
}

function majState(state)
{
	$(".bandeau li.wait").show();
	
	var data = {state: state};
	var label = $(".listLabel.label_selected > span");
	if(label.length > 0) {data.label = label.text();}
	
	$.ajax({
		url: base_url+"/issues/liste",
		data: data,
		type: 'POST'
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		$("#listeIssue").html(data);
	})
	.fail(function() {
		$(".bandeau li.wait").hide();
		alert("Désolé j'ai crashé :o");
	});
}

function majLabel(label)
{
	$(".bandeau li.wait").show();
	
	var open = $(".label_open.label_selected");
	var close = $(".label_close.label_selected");
	var state = '';
	
	if(open.length > 0) {state = 'open';}
	if(close.length > 0) {state = 'close';}
	
	var data = {state: state, label: label};
	
	$.ajax({
		url: base_url+"/issues/liste",
		data: data,
		type: 'POST'
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		$("#listeIssue").html(data);
	})
	.fail(function() {
		$(".bandeau li.wait").hide();
		alert("Désolé j'ai crashé :o");
	});
}

$(document).ready(function()
{
	$(".cont").on("submit", "#addBug", function() {
		$(".bandeau li.wait").show();
		
		var titre = $("#addBugTitre").val();
		var body = $("#addBugBody").val();
		
		$.ajax({
			url: base_url+"/issues/addBug",
			data: {titre: titre, body: body},
			type: 'POST'
		})
		.done(function(data)
		{
			$(".bandeau li.wait").hide();
			page($(".menuSelected"), $(".menuSelected").attr("id"));
		})
		.fail(function()
		{
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
		});
		
		return false;
	});
	
	$(".cont").on("click", ".commentIssue", function() {
		$("#AddCommentIssueNumber").val($(this).attr('id'));
		$("#titreAddCommentIssue").html($(this).parent().children('a').html());
		$("#dialogAddCommentIssue").dialog("open");
	});
	
	$(".cont").on("click", ".label_open", function() {
		if($(".label_close.label_selected").length > 0)
		{
			$(".label_close").removeClass('label_selected');
			$(".label_open").addClass('label_selected');
		}
		
		majState('open');
	});
	
	$(".cont").on("click", ".label_close", function() {
		if($(".label_open.label_selected").length > 0)
		{
			$(".label_open").removeClass('label_selected');
			$(".label_close").addClass('label_selected');
		}
		
		majState('close');
	});
	
	$(".cont").on("click", ".listLabel", function() {
		if(!$(this).hasClass('label_selected'))
		{
			if($(".listLabel.label_selected").length > 0)
			{
				$(".listLabel").removeClass('label_selected');
			}
			
			$(this).addClass('label_selected');
			majLabel($(this).children('span').text());
		}
		else
		{
			$(".listLabel").removeClass('label_selected');
			majLabel('');
		}
	});
});