/**
 * Met à jour les po du perso dans le bandeau
 */
function maj_po_bandeau()
{
	$("#poPerso").show();
	$("#poPerso").html($("#PersoViewPo").val());
}

/**
 * Change de page
 * 
 * @param string nom
 * @param bool   suite
 * @param string idPerso
 */
function contPersoView(context, url, idPerso, suite, tri)
{
	$(".bandeau li.wait").show();
	url = base_url+"/perso/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(suite == undefined || suite == false) {$("#suite").val("0");}
	suite = $("#suite").val();
	
	var triRow = $("#triRow").val();
	var triSens = $("#triSens").val();
	
	$.ajax({
		url: url,
		data: {suite: suite, triRow: triRow, triSens: triSens, idPerso: idPerso},
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
		
		if(suite == 1) {$(".cont_persoView").html(data);}
		else {$("#ViewPersoTbody").append(data);}
		
		if($(this).attr("id") == "addItem")
		{
			$('#AddItem_date').datetimepicker({
				timeFormat: 'HH:mm:ss',
				dateFormat: 'dd/mm/yy'
			});
			$('#AddItem_date').datetimepicker('setDate', (new Date()) );
			
			item_autocomplete("#AddItem");
			item_autocomplete("#Stat1", "stat");
			item_autocomplete("#Stat2", "stat");
			item_autocomplete("#Stat3", "stat");
			item_autocomplete("#Stat4", "stat");
			item_autocomplete("#Stat5", "stat");
			item_autocomplete("#Stat6", "stat");
		}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

/**
 * Créer l'auto-complétion pour la recherche d'item/stat
 * 
 * @param string context
 * @param string params
 */
function item_autocomplete(context, params)
{
	if(params == undefined) {params = "all";}
	
	//http://blog.aurelien-gerits.be/2013/01/26/jquery-ui-autocomplete-avec-une-requete-ajax-json/
	$(context+"_name").autocomplete({
		minLength: 2,
		source: function(req, add) {
			$.ajax({
	            url:'itemSearch',
	            type:"post",
	            dataType: 'json',
	            data: {search: req.term, params: params},
	            async: true,
	            cache: true,
	            success: function(data)
	            {
	                var suggestions = [];  
	                
	                //process response  
	                $.each(data, function(i, val){  
	                 	suggestions.push({"id": val.id, "value": val.value, "text": val.text});  
	             	});  
	             	
	             	//pass array to callback  
	             	add(suggestions); 
	            }
	        });
	    },
		select: function( event, ui ) {
			$(context+"_name").val(ui.item.text);
			$(context+"_idItem").val(ui.item.id);
			
			if(context == "#AddItem") {$("#EtatItem").attr("src", "../img/tick.png");}
			
			return false;
		}
	})
    .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.value + "</a>" )
        .appendTo( ul );
    };;
}

/**
 * Pour la fenêtre de dialog quand un item est acheté
 */
function dialogVendu()
{
	$("#dialogVendu").dialog({
		autoOpen: false,
		height: 150,
		width: 550,
		modal: true,
		buttons:
		{
			"C'est bien ça": function()
			{
				var ref = $("#dialogVenduRefItem").val();
				var type = $("#TypeAchatVendu").val();
				var po = $("#poGagneVendu").val();
				
				$.ajax({
					url: base_url+"/perso/itemVendu",
					data: {type: type, po: po, ref: ref},
					type: 'post'
				})
				.done(function() {
					$("#dialogVendu").dialog("close");
					
					var button = $("button.selected");
					var idPerso = $("#PersoViewId").val();
					
					contPersoView(button, $(button).attr("id"), idPerso);
				})
				.fail(function() {alert("Désolé j'ai crashé");});
			},
			"Annuler": function() {$(this).dialog("close");}
		},
		close: function()
		{
			$(".trSelected").removeClass("trSelected");
			$("#TypeAchatVendu").val("rachat");
			$("#poGagneVendu").val("");
		}
	});
	
	$(".ui-dialog-titlebar").removeClass("ui-corner-all");
}

/**
 * Pour la fenêtre de dialog quand un item est mit en vente
 */
function dialogMeV()
{
	$("#dialogMeV").dialog({
		autoOpen: false,
		height: 190,
		width: 400,
		modal: true,
		buttons:
		{
			"C'est bien ça": function()
			{
				var ref = $("#dialogMeVRefItem").val();
				var enchere = $("#dialogMeVEnchere").val();
				var rachat = $("#dialogMeVRachat").val();
				var date = $("#dialogMeVDate").val();
				var duree = $("#dialogMeVDuree").val();
				
				$.ajax({
					url: base_url+"/perso/itemMeV",
					data: {
						ref: ref,
						enchere: enchere,
						rachat: rachat,
						date: date,
						duree: duree
					},
					type: 'post'
				})
				.done(function() {
					$("#dialogMeV").dialog("close");
					
					var button = $("button.selected");
					var idPerso = $("#PersoViewId").val();
					
					contPersoView(button, $(button).attr("id"), idPerso);
				})
				.fail(function() {alert("Désolé j'ai crashé");});
			},
			"Annuler": function() {$(this).dialog("close");}
		},
		close: function()
		{
			$(".trSelected").removeClass("trSelected");
			
			$("#dialogMeVEnchere").val("");
			$("#dialogMeVRachat").val("");
			$("#dialogMeVDate").val("");
			$("#dialogMeVDuree").val("3");
			
		}
	});
	
	$(".ui-dialog-titlebar").removeClass("ui-corner-all");
}

/**
 * Ajoute des points dans les nombres
 * http://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript
 * 
 * @param {Object} x
 * 
 * @return string
 */
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).ready(function()
{
	//Liste des persos
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
		.done(function() {page($(".menuSelected"), "perso/liste");})
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
		var po = $(this).val();
		
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
			$(".bandeau li.wait").show();
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
	//Liste des persos
	
	
	
	//Vue d'un perso
	$(".cont").on("click", "#buttonPersoView button", function() {
		$("#buttonPersoView button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		var idPerso = $("#PersoViewId").val();
		contPersoView(this, $(this).attr("id"), idPerso);
	})
	
	$(".cont").on("click", "#ViewPersoSuite", function() {
		$(this).hide();
		
		var button = $("button.selected");
		var idPerso = $("#PersoViewId").val();
		contPersoView(button, $(button).attr("id"), idPerso, true);
	});
	
	$(".cont").on("click", "table#ViewPerso thead td", function() {
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
		var idPerso = $("#PersoViewId").val();
		contPersoView(button, $(button).attr("id"), idPerso, false, $(this).attr("class"));
	});
	
	$(".cont").on('submit', 'form#FormAddItem', function() {
		$(".bandeau li.wait").show();
		
		var token = $("#TokenForm").val();
		var idPerso = $("#PersoViewId").val();
		var idItem = $("#AddItem_idItem").val();
		var idStat1 = $("#Stat1_idItem").val();
		var idStat2 = $("#Stat2_idItem").val();
		var idStat3 = $("#Stat3_idItem").val();
		var idStat4 = $("#Stat4_idItem").val();
		var idStat5 = $("#Stat5_idItem").val();
		var idStat6 = $("#Stat6_idItem").val();
		var enchere = $("#AddItem_enchere").val();
		var rachat = $("#AddItem_rachat").val();
		var Uenchere = $("#AddItem_Uenchere").val();
		var Urachat = $("#AddItem_Urachat").val();
		var Unb = $("#AddItem_Unb").val();
		var date = $("#AddItem_date").val();
		var duree = $("#AddItem_duree").val();
		var notes = $("#AddItem_notes").val();
		
		$.ajax({
			url: base_url+"/perso/addVente",
			type: 'POST',
			data: {
				idPerso : idPerso,
				idItem : idItem,
				idStat1 : idStat1,
				idStat2 : idStat2,
				idStat3 : idStat3,
				idStat4 : idStat4,
				idStat5 : idStat5,
				idStat6 : idStat6,
				enchere : enchere,
				rachat : rachat,
				Uenchere : Uenchere,
				Urachat : Urachat,
				Unb : Unb,
				date : date,
				duree : duree,
				notes : notes,
				token : token
			}
		})
		.done(function(data)
		{
			var button = $("button.selected");
			var idPerso = $("#PersoViewId").val();
			
			contPersoView(button, $(button).attr("id"), idPerso);
		})
		.fail(function(data) {
			$(".bandeau li.wait").hide();
			
			if(data.status == 409) {console.log('Token');}
			else {alert("Désolé j'ai crashé :o");}
		});
		
		return false;
	});
	
	$('.cont').on('click', '#ViewPersoTbody button.ItemVendu', function() {
		
		var indexEnchere = 0;
		var indexRachat = 0;
		
		if($("button.selected").attr("id") == 'vente') {indexEnchere = 2;}
		if($("button.selected").attr("id") == 'attente') {indexEnchere = 1;}
		indexRachat = indexEnchere+1;
		
		
		var ref = $(this).attr("id");
		var trSelect = $(this).parents("tr");
		
		$(trSelect).addClass("trSelected");
		
		var type = $("#TypeAchatVendu").val();
		var calc = 0;
		var val = 0;
		
		$(trSelect).children('td').each(function(index, element)
		{
			if(index == indexEnchere && type == 'enchere') {val = parseInt($("#"+ref+"_enchere").val());}
			if(index == indexRachat && type == 'rachat') {val = parseInt($("#"+ref+"_rachat").val());}
		});
		
		var calc = 0;
		if(val > 0) {calc = val-((val*6)/100)-1;}
		$("#poGagneVendu").val(numberWithCommas(calc));
		
		$("#dialogVenduRefItem").val($(this).attr("id"));
		$("#dialogVendu").dialog("open");
	});
	
	$('body').on('change', '#TypeAchatVendu', function() {
		var indexEnchere = 0;
		var indexRachat = 0;
		
		if($("button.selected").attr("id") == 'vente') {indexEnchere = 2;}
		if($("button.selected").attr("id") == 'attente') {indexEnchere = 1;}
		indexRachat = indexEnchere+1;
		
		
		var trSelect = $("tr.trSelected");
		var ref = $(trSelect).find('button.ItemVendu').attr("id");
		var type = $("#TypeAchatVendu").val();
		var calc = 0;
		var val = 0;
		
		$(trSelect).children('td').each(function(index, element)
		{
			if(index == indexEnchere && type == 'enchere') {val = parseInt($("#"+ref+"_enchere").val());}
			if(index == indexRachat && type == 'rachat') {val = parseInt($("#"+ref+"_rachat").val());}
		});
		
		var calc = val-((val*6)/100)-1;
		if(calc < 0) {calc = 0;}
		
		$("#poGagneVendu").val(numberWithCommas(calc));
	});
	
	$('.cont').on('click', '#ViewPersoTbody button.ItemMeV', function() {
		var ref = $(this).attr("id");
		var trSelect = $(this).parents("tr");
		
		$(trSelect).addClass("trSelected");
		if($(".trSelected + tr").find('td').length == 2) {$(".trSelected + tr").addClass("trSelected");}
		
		var enchere = 0;
		var rachat = 0;
		
		$(trSelect).children('td').each(function(index, element)
		{
			if(index == 1) {enchere = $(trSelect).find('td').eq(index).text();}
			if(index == 2) {rachat = $(trSelect).find('td').eq(index).text();}
		});
		
		var enchere_unite = enchere;
		var rachat_unite = rachat;
		
		var nbPiece = $("#"+ref+"_nbPiece").val();
		if(nbPiece > 1)
		{
			enchere_unite = substr($(".trSelected:first + tr").find('td').eq(0).text(), 0, -4);
			rachat_unite = substr($(".trSelected:first + tr").find('td').eq(1).text(), 0, -4);
		}
		
		$("#dialogMeVEnchere").val(enchere);
		$("#dialogMeVRachat").val(rachat);
		
		$("#dialogMeVUEnchere").val(enchere_unite);
		$("#dialogMeVURachat").val(rachat_unite);
		$("#dialogMeVUnb").val(nbPiece);
		
		$("#dialogMeVRefItem").val($(this).attr("id"));
		$("#dialogMeVDate").datetimepicker('setDate', (new Date()) );
		
		$("#dialogMeV").dialog("open");
	});
	
	//Vue d'un perso
});