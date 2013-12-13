function startStepMaj(step)
{
	$.ajax({
		url: base_url+'/maj/'+step
	})
	.done(function() {
		$("#majStep"+step).css("color", "#00C000");
		step++;
		startStepMaj(step);
	})
	.fail(function(data) {
		console.log(data);
		$("#StatusMaj").text("Erreur dans la maj à l'étape "+step+" : "+data.responseText);
		$("#StatusMaj").css("color", "red");
		endFail();
	});
}

function endFail()
{
	$(".allStepMaj").children('p').each(function(index, element)
	{
		console.log($(".allStepMaj").find('p').eq(index).css("color"));
		if($(".allStepMaj").find('p').eq(index).css("color") == 'rgb(0, 0, 0)')
		{
			$(".allStepMaj").find('p').eq(index).css("color", "red");
		}
	});
}

$(document).ready(function()
{
	$("#maj").click(function() {startStepMaj(1);});
});