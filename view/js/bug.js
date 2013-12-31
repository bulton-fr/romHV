$(document).ready(function()
{
	$(".cont").on("submit", "#addBug", function() {
		$(".bandeau li.wait").show();
		
		var titre = $("#addBugTitre").val();
		var body = $("#addBugBody").val();
		
		$.ajax({
			url: base_url+"/addBug",
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
			alert("Désolé j'ai crashé :o")
		});
		
		return false;
	});
});