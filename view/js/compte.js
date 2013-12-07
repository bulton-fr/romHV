$(document).ready(function()
{
	$(".cont").on('submit', 'form#SendChangeMDP', function() {
		$(".bandeau li.wait").show();
		
		var oldMDP = $("input#oldMDP").val();
		var newMDP = $("input#newMDP").val();
		
		if(oldMDP == "" || oldMDP == undefined || newMDP == "" || newMDP == undefined)
		{
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
			
			return false;
		}
		
		$.ajax({
			url: base_url+"/monCompte",
			data: {oldMDP: oldMDP, newMDP: newMDP},
			type: 'POST',
			dataType: 'json'
		})
		.done(function(data) {
			if(data.status == 200) {page("monCompte/ok");}
			else
			{
				$(".bandeau li.wait").hide();
				alert("Désolé j'ai crashé :o");
			}
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
		});
		
		return false;
	});
});