/**
 * Change de page
 * 
 * @param string nom
 * @param bool   color
 * @param string idPerso
 */
function contAttente(suite, tri)
{
	$(".bandeau li.wait").show();
	url = base_url+"/enAttente"; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(suite == undefined || suite == false) {$("#suite").val("0");}
	suite = $("#suite").val();
	
	var triRow = $("#triRow").val();
	var triSens = $("#triSens").val();
	
	$.ajax({
		url: url,
		data: {suite: suite, triRow: triRow, triSens: triSens},
		type: 'POST',
		dataType: 'html'
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		
		suite = parseInt($("#suite").val());
		suite++;
		$("#suite").val(suite);
		
		if(suite == 1) {$(".contAttente").html(data);}
		else {$("#AttenteTbody").append(data);}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

$(document).ready(function()
{
	$(".cont").on("click", "#AttenteSuite", function() {
		$(this).remove();
		contAttente(true);
	});
	
	$(".cont").on("click", "table#Attente thead td", function() {
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
			
			if($(this).attr("class") == "attenteDate") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		
		contAttente(false, $(this).attr("class"));
	})
});