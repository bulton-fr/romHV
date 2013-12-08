/**
 * Change de page
 * 
 * @param string nom
 * @param bool   color
 * @param string idPerso
 */
function contVentes(context, url, suite, tri)
{
	$(".bandeau li.wait").show();
	url = base_url+"/ventes/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(suite == undefined || suite == false) {$("#suite").val("0");}
	suite = $("#suite").val();
	
	var triRow = $("#triRow").val();
	var triSens = $("#triSens").val();
	
	$.ajax({
		url: url,
		data: {suite: suite, triRow: triRow, triSens: triSens},
		type: 'POST',
		dataType: 'html',
		context: context
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		
		$(".selected").removeClass('selected');
		$(this).addClass('selected');
		
		suite = parseInt($("#suite").val());
		suite++;
		$("#suite").val(suite);
		
		if(suite == 1) {$(".contVentes").html(data);}
		else {$("#VentesTbody").append(data);}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

$(document).ready(function()
{
	$(".cont").on("click", "#buttonVentes button", function() {
		$("#buttonVentes button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		contVentes(this, $(this).attr("id"));
	})
	
	$(".cont").on("click", ".suite", function() {
		$(this).remove();
		
		var button = $("button.selected");
		contVentes(button, $(button).attr("id"), true);
	});
	
	$(".cont").on("click", "table#Ventes thead td", function() {
		var triRow = $("#triRow").val();
		
		if(triRow == $(this).attr("class"))
		{
			var triSens = $("#triSens").val();
			if(triSens == "ASC") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		else
		{
			$("#triRow").val($(this).attr("class"));
			
			if($(this).attr("class") == "venteDate") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		
		var button = $("button.selected");
		contVentes(button, $(button).attr("id"), false, $(this).attr("class"));
	})
});