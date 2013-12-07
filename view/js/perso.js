$(document).ready(function()
{
	$(".cont")
	
	.on('submit', 'form#createPerso', function() {
		
		var nom = $("form#createPerso input#nomPerso").val();
		var po = $("form#createPerso input#po").val();
		
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/create",
			data: {nom: nom, po: po},
			type: 'POST'
		})
		.done(function() {page("perso/liste");})
		.fail(function()
		{
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
		
		return false;
	})
	
	.on('click', 'span.nomPerso', function() {
		var nom = $(this).html();
		$(this).replaceWith('<input type="text" id="nomPerso" value="'+nom+'" />');
		$('p.ListeDesPersos input#nomPerso').focus();
	})
	.on('click', 'span.poPerso', function() {
		var po = $(this).html();
		$(this).replaceWith('<input type="text" class="po" id="poPerso" value="'+po+'" />');
		$('p.ListeDesPersos input#poPerso').focus();
	})
	
	.on('blur', 'p.ListeDesPersos input#nomPerso', function() {
		var idPerso = $(this).parent().attr('id');
		var nom = $(this).val();
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/edit",
			data: {idPerso: idPerso, nom: nom},
			type: 'POST'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			$('p.ListeDesPersos input#nomPerso').replaceWith('<span class="nomPerso">'+data+'</span>');
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
	})
	.on('blur', 'p.ListeDesPersos input#poPerso', function() {
		var idPerso = $(this).parent().attr('id');
		var po = $(this).html();
		
		if(po == "") {po = 0;}
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/edit",
			data: {idPerso: idPerso, po: po},
			type: 'POST'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			$('p.ListeDesPersos input#poPerso').replaceWith('<span class="poPerso">'+data+'</span>');
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
	})
	
	.on('click', 'p.ListeDesPersos img', function(){
		var idPerso = $(this).parent().attr('id');
		
		if(confirm('Voulez-vous vraiment supprimer le personnage ?'))
		{
			$.ajax({
				url: base_url+"/perso/delete",
				data: {idPerso: idPerso},
				type: 'POST'
			})
			.done(function(data) {page("perso/liste");})
			.fail(function() {
				$(".bandeau li.wait").hide();
				alert("Désolé j'ai crashé :o")
			});
		}
	});
});