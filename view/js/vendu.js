/**
 * Change de page
 * 
 * @param string nom
 * @param bool   suite
 * @param string idPerso
 */
function contVendu(context, url, suite, tri)
{
	$(".bandeau li.wait").show();
	url = base_url+"/vendu/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
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
		
		if(suite == 1) {$(".contVendu").html(data);}
		else {$("#VenduTbody").append(data);}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

$(document).ready(function()
{
	$(".cont").on("click", "#buttonVendu button", function() {
		$("#buttonVendu button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		contVendu(this, $(this).attr("id"));
	})
	
	$(".cont").on("click", "#VenduSuite", function() {
		$(this).remove();
		
		var button = $("button.selected");
		contVendu(button, $(button).attr("id"), true);
	});
	
	$(".cont").on("click", "table#Vendu thead td", function() {
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
			
			if($(this).attr("class") == "venduDate") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		
		var button = $("button.selected");
		contVendu(button, $(button).attr("id"), false, $(this).attr("class"));
	})
});