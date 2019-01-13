// ADD EDIT ADMIN SPOT
	function verifAddSpotAdmin(f)
	{
		var nom  = document.getElementById('name').value;
		var city = document.getElementById('city').value;
		var checkValidAddSpotName = document.getElementById('name').validity.valid;
		var checkValidAddSpotCity = document.getElementById('city').validity.valid;

		if(nom != "" && city != "" && checkValidAddSpotName == true && checkValidAddSpotCity == true)
			return true;
		else
			$("#city").addClass('sendInvalid');
			$("#name").addClass('sendInvalid');
			return false;
	}

	function verifModifSpotAdmin(f)
	{
		var nom  = document.getElementById('name').value;
		var city = document.getElementById('city').value;
		var checkValidModifSpotName = document.getElementById('name').validity.valid;
		var checkValidModifSpotCity = document.getElementById('city').validity.valid;

		if(nom != "" && city != "" && checkValidModifSpotName == true && checkValidModifSpotCity == true)
			return true;
		else
			$("#city").addClass('sendInvalid');
			$("#name").addClass('sendInvalid');
			return false;
	}

	// TO UPPER CASE 
		function upperCaseNom(champ) {
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i = 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('name').value = champFinal2;
		}

		function upperCaseNomEvent(champ) {
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i = 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('nameSpot').value = champFinal2;
		}

		function upperCaseCity(champ) {
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('city').value = champFinal2;
		}

		function upperCaseAdress(champ) {
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('address').value = champFinal2;
		}

		function upperCaseChangeRequest(champ) {
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('changeRequest').value = champFinal2;
		}
	// TO UPPER CASE
// ADD EDIT ADMIN SPOT






// ADD EDIT ADMIN GENRE
	function verifAddGenreAdmin(f)
	{
		var genre = document.getElementById('nameGenre').value;
		var checkValidAddGenre = document.getElementById('nameGenre').validity.valid;
		
		if(genre != "" && checkValidAddGenre == true)
			return true;
		else
			$("#nameGenre").addClass('sendInvalid');
			return false;
	}

	function verifModifGenreAdmin(f)
	{
		var genre = document.getElementById('nameGenre').value;
		var checkValidModifGenre = document.getElementById('nameGenre').validity.valid;

		if(genre != ""  && checkValidModifGenre == true)
			return true;
		else
			$("#nameGenre").addClass('sendInvalid');
			return false;
	}

	// TO UPPER CASE 
		function upperCaseGenre(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('nameGenre').value = champFinal2;
		}

		function upperCaseChangeRequest(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('changeRequest').value = champFinal2;
		}
	// TO UPPER CASE
// ADD EDIT ADMIN GENRE





// ADMIN ARTISTE
	function verifAddArtisteAdmin(f)
	{
		var nom = document.getElementById('name').value;
		var checkValidAddArtiste = document.getElementById('name').validity.valid;

		if(nom != "" && checkValidAddArtiste == true)
			return true;
		else
			$("#name").addClass('sendInvalid');
			return false;
	}

	function verifModifArtisteAdmin(f)
	{
		var nom = document.getElementById('name').value;
		var checkValidModifArtiste = document.getElementById('name').validity.valid;

		if(nom != "" && checkValidModifArtiste == true)
			return true;
		else
			$("#name").addClass('sendInvalid');
			return false;
	}

	// TO UPPER CASE 
		function upperCaseName(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('name').value = champFinal2;
		}

		function upperCaseNameArtist(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('nameArtist').value = champFinal2;
		}

		function upperCaseLabel(champ) 
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('label').value = champFinal2;
		}

		function upperCaseCountry(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('country').value = champFinal2;
		}

		function upperCaseChangeRequest(champ)
		{
			var champTest = champ.split(" ");
			var champFinal = new Array();
			for(var i= 0; i < champTest.length; i++)
			{
				champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
			}
			var champFinal2 = champFinal.join(' ');
			document.getElementById('changeRequest').value = champFinal2;
		}
	// TO UPPER CASE
// ADMIN ARTISTE 








// ADMIN UTILISATEUR 
	function verifAddUsersAdmin(f)
	{
		$("#AjoutUser").addClass("invalidAddUsersForm");
		var username  = document.getElementById('username').value;
		var password  = document.getElementById('password').value;
		var password2 = document.getElementById('password2').value;
		var email     = document.getElementById('email').value;
		var checkValidAddUsersUsername = document.getElementById('username').validity.valid;
		var checkValidAddUsersPassword = document.getElementById('password').validity.valid;
		var checkValidAddUsersPassword2 = document.getElementById('password2').validity.valid;
		var checkValidAddUsersEmail = document.getElementById('email').validity.valid;

		if(username != "" && password != "" && password2 != "" && email != "" && checkValidAddUsersUsername == true && checkValidAddUsersPassword == true && checkValidAddUsersPassword2 == true && checkValidAddUsersEmail == true) {
			if(password != password2)
			{
				$(".invalidPass").html("Les mots de passes que vous avez choisi ne sont pas identiques.");
				return false;
			}
			else
			{				
				return true;
			}
		}
		else {
		  	return false;
		}
	}

	function upperCaseUsername(champ)
	{
		var champTest = champ.split(" ");
		var champFinal = new Array();
		for(var i= 0; i < champTest.length; i++)
		{
			champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
		}
		var champFinal2 = champFinal.join(' ');
		document.getElementById('username').value = champFinal2;
	}
// ADMIN UTILISATEUR 





// EVENT 
	function verifAddQuickEventAdmin(f)
	{
		var linkFb = document.getElementById('linkFb').value;
		var checkValidLinkFbAddQuick = document.getElementById('linkFb').validity.valid;

		if(linkFb != "" && checkValidLinkFbAddQuick == true)
			return true;
		else
			$("#linkFb").addClass('sendInvalid');
			return false;
	}
	
	function upperCaseAddEvent(champ)
	{
		var champTest = champ.split(" ");
		var champFinal = new Array();
		for(var i= 0; i < champTest.length; i++)
		{
			champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
		}
		var champFinal2 = champFinal.join(' ');
		document.getElementById('name').value = champFinal2;
	}

	function upperCaseEditEvent(champ)
	{
		var champTest = champ.split(" ");
		var champFinal = new Array();
		for(var i= 0; i < champTest.length; i++)
		{
			champFinal = champFinal.concat(champTest[i].charAt(0).toUpperCase() + champTest[i].substring(1).toLowerCase());
		}
		var champFinal2 = champFinal.join(' ');
		document.getElementById('name').value = champFinal2;
	}
// EVENT 





// ADD QUICK
	function verifAddQuickEventAdmin(f)
	{
		var linkFb = document.getElementById('linkFb').value;
		var checkValidLinkFbAddQuick = document.getElementById('linkFb').validity.valid;

		if(linkFb != "" && checkValidLinkFbAddQuick == true)
			return true;
		else
			$("#linkFb").addClass('sendInvalid');
			return false;
	}
// ADD QUICK