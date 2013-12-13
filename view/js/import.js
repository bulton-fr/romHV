nbRelanceStep2 = 0;

function startStepMaj(step)
{
	var maxStep = 10;
	if(step > maxStep)
	{
		$(".bandeau li.wait").hide();
		$("#StatusMaj").text("Mise à jour terminé avec succès :)");
		$("#StatusMaj").css("color", "#00C000");
		
		return true;
	}
	
	$("#majStep"+step).css("font-weight", "bold");
	
	$.ajax({
		url: base_url+'/maj/'+step
	})
	.done(function(data) {
		
		//Fatal error: Maximum execution time of 30 seconds exceeded
		var dataTrim = trim(data);
		if(dataTrim != "")
		{
			var error = trim(substr(dataTrim, 0, strpos(dataTrim, ' in ')));
			
			//Si maximum execution of time, on relance.
			if(step == 2 && error == "Fatal error: Maximum execution time of 30 seconds exceeded")
			{
				nbRelanceStep2++;
				$("#StatusMaj").text("Etape 2 relancé ("+nbRelanceStep2+").");
				startStepMaj(2);
			}
			else
			{
				$("#StatusMaj").text("Erreur dans la maj à l'étape "+step+" : "+error);
				$("#StatusMaj").css("color", "red");
				endFail();
			}
		}
		else
		{
			$("#majStep"+step).css("color", "#00C000");
			step++;
			startStepMaj(step);
		}
	})
	.fail(function(data) {
		$("#StatusMaj").text("Erreur dans la maj à l'étape "+step+" : "+data.responseText);
		$("#StatusMaj").css("color", "red");
		endFail();
	});
}

function endFail()
{
	$(".bandeau li.wait").hide();
	
	$(".allStepMaj").children('p').each(function(index, element)
	{
		if($(".allStepMaj").find('p').eq(index).css("color") == 'rgb(0, 0, 0)')
		{
			$(".allStepMaj").find('p').eq(index).css("color", "red");
		}
	});
}

$(document).ready(function()
{
	$("#maj").click(function()
	{
		$(".bandeau li.wait").show();
		
		$("#StatusMaj").text("");
		$(".allStepMaj").children('p').each(function(index, element)
		{
			$(".allStepMaj").find('p').eq(index).css("color", "rgb(0, 0, 0)");
			$(".allStepMaj").find('p').eq(index).css("font-weight", "normal");
		});
		
		startStepMaj(1);
	});
});