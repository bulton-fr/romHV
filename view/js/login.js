function login_crash()
{
	$(".result_login").html("<p>Venez avec moi faire un tour à Dalanis :)");
}

$(document).ready(function()
{
	$('form.login').submit(function() {
		var login = $("#login").val();
		var mdp = $("#mdp").val();
		
		$(".result_login").html("<p>Vérification en cours...</p><p class='loader'></p>");
		$(".form_login").hide();
		$(".result_login").show();
		
		if(login == "" || mdp == "") {login_crash();}
		else
		{
			$.ajax({
				url: base_url,
				data: {login: login, mdp: mdp},
				type: 'POST',
				dataType: 'json'
			})
			.done(function(data)
			{
				if(data.status == 200)
				{
					var html =  "<p style='margin-top: 2px;'>Oh bonjour "+data.login+".</p>";
					    html += "<p>Mes excuses, je ne vous avais pas reconnu.</p>";
					    html += "<p>Je vous envoi sur votre interface immédiatement.</p>";
					
					$(".result_login").html(html);
					
					setTimeout(function() {
						window.location.href = base_url;
					}, 3000);
				}
				else {login_crash();}
			})
			.fail(function() {login_crash();});
		}
		
		return false;
	});
});