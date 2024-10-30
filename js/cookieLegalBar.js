var arrayJsonNews = "";
jQuery(this).load(function(){
	(jQuery).ajax({
		type: 'POST',
		jsonp: "callback",
		url: "http://www.cookielegal.it/js/loadNews.php",
		data: {destinatario: "all",format: "json"},
		dataType: 'jsonp',
		crossDomain: true,
		async:true
		}).done(function(data) {
			arrayJsonNews = jQuery.makeArray(data);
		}).fail(function() {
			var result = document.getElementById("newsCookieLegal");
			result.innerHTML = "<div class=\"blockNewsCookieLegal\"><p>Nessuna Comunicazione</p></div>";
		}).complete(function(){
			var result = jQuery("#newsCookieLegal");
			var num = arrayJsonNews[0].length;
			var arr = jQuery.makeArray(arrayJsonNews[0]);
			var i = 0;
			for(var i = 0; i < num; i++){
				var arr1 = jQuery.makeArray(arr[i]);
				var desc = "";
				if(arr1[0].desc != ""){
					desc = '<div class="descrizioneNewsCookieLegal">'+arr1[0].desc+' <a href="#">Continua a leggere questa comunicazione!</a></div>';	
				}
				result.append( 
					'<div id="blockNewsCookieLegal-'+i+'" class="blockNewsCookieLegal" onclick="openNews('+i+')">'+
						'<div id="headerNewsCookieLegal" class="row headerCl">'+
							'<div class="infoNews"><p>'+
								'<span class="leftCL" style="float:left;">'+arr1[0].title+'</span>'+
								'<span class="rightCL" style="float:right;">'+arr1[0].date+'</span>'+
							'</p></div>'+
							desc+
						'</div>'+
						'<div id="bodyNewsCookieLegal" class="row bodyCL hidden">'+
							arr1[0].text+
						'</div>'+
					'</div>'
				);
			}
			
		});
	}
);

function openNews(numNews){
	if(jQuery('#blockNewsCookieLegal-'+numNews+" #bodyNewsCookieLegal").hasClass("hidden")){
		jQuery('#blockNewsCookieLegal-'+numNews+" #bodyNewsCookieLegal").removeClass("hidden");
	}else{
		jQuery('#blockNewsCookieLegal-'+numNews+" #bodyNewsCookieLegal").addClass("hidden");
	}
}

function accept(){
	var dat = new Date();
	dat.setTime(dat.getTime() + (365*2*24*60*60*1000));
	 var expires = "expires="+dat.toUTCString();
	document.cookie="CookieLegal=accept; "+expires+"; path=/";
	location.reload();
}

function not_accept(){
	var dat = new Date();
	dat.setTime(dat.getTime() + (365*2*24*60*60*1000));
	 var expires = "expires="+dat.toUTCString();
	document.cookie="CookieLegal=not-accept; "+expires+"; path=/";
	location.reload();
}

function leggiCookie(nomeCookie){
	if (document.cookie.length > 0){
    	var inizio = document.cookie.indexOf(nomeCookie + "=");
    	if (inizio != -1){
      		inizio = inizio + nomeCookie.length + 1;
      		var fine = document.cookie.indexOf(";",inizio);
      		if (fine == -1) fine = document.cookie.length;
      		return unescape(document.cookie.substring(inizio,fine));
    	}else{
       		return "";
    	}
  	}
  	return "";
}

function hideBanner(){
	var cookie = leggiCookie("CookieLegal");
	if(cookie != ""){
		var bar = document.getElementById("CookieLegal");
		bar.setAttribute("style","display:none !important;");
	}
}

function loadBar(){
	hideBanner();
}

