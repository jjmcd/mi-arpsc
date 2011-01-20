	
	//functions.js	
	
	function runajax(objID, serverPage) {
		
		//Create a boolean variable to check for a valid Internet Explorer instance.
		var xmlhttp = false;
		
		//Check if we are using IE.
		try {
			//If the javascript version is greater than 5.
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			//If not, then use the older active x object.
			try {
				//If we are using Internet Explorer.
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				//Else we must be using a non-IE browser.
				xmlhttp = false;
			}
		}
		//If we are using a non-IE browser, create a javascript instance of the object.
		if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
			xmlhttp = new XMLHttpRequest();
		}
		
		var obj = document.getElementById(objID);
		xmlhttp.open("GET", serverPage);
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				obj.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.send(null);
	}
	
	//Function to clear error messages.
	function clearmes (){
		//Clear any old error messages.
		document.getElementById("errordiv").innerHTML = "";
	}
	
	//Function to show a loading message.
	function showload (){
		document.getElementById("middiv").innerHTML = "<b>Loading...</b>";
	}
	
	//Variable dictating how long to wait to refresh the gallery.
	var refreshrate = 1000;
	
	function uploadimg (theform){
		//Submit the form.
		theform.submit();
		//Clear any old error messages.
		clearmes();
		//Show loading.
		showload();
		//Re-load the full size image.
		setTimeout ('runajax ("middiv","midpic.php")',refreshrate);
		//Re-load the navigation.
		setTimeout ('runajax ("picdiv","picnav.php")',refreshrate);
	}
	
	function removeimg (theimg){
		runajax ("errordiv","delpic.php?pic=" + theimg + "");
		//Re-load the full size image.
		setTimeout ('runajax ("middiv","midpic.php")',refreshrate);
		//Re-load the navigation.
		setTimeout ('runajax ("picdiv","picnav.php")',refreshrate);
	}