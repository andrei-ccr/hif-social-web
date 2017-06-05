function IsDiscussionPage() {
	if($(".f-c-discussion").length) return true;
	else return false;
}

function GetRelatedFeelings(ftxt) {
	if(!IsDiscussionPage()) {
		var request = $.ajax({
			url: "sys.php",
			method: "POST",
			data: { feel : ftxt },
			dataType: "html"
		});
		 
		request.done(function( list ) {
			if($(".related-container").length) {
				$(".related-container").html("<h2>Related feelings</h2>" + list);
			} else {
				$( ".feelings-container" ).prepend('<div class="related-container"><h2>Related feelings</h2>' + list + '</div>');
			}				
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			$( ".feelings-container" ).append( "Can't load related feelings :(" );
		});
	}
}

function ProccessComment()  {
	var comment = $('#comment-insert').val().trim();
	if(!comment || 0 === comment.length) { $('#comment.insert').css("border-color", "red"); }
	else {
		var fid = $('.feel').data("id"); //There will be only 1 feel so it's safe to do this, but should find a better way.
		var request = $.ajax({
			url: "sys.php",
			method: "POST",
			data: { insert_com : comment, feel_id : fid },
			dataType: "html"
		});
		 
		request.done(function( list ) {
			console.log(list);
			location.reload();
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			console.log("Failed to register the comment");
		});
	}
}
function ProccessFeeling() {
	var feel = $('#feeling-insert').val().trim();
	if(!feel || 0 === feel.length) $('#feeling-insert').css("border-color", "red");
	else {
		$('#feeling-insert').css("border-color", "#222");
		console.log(feel);
		var request = $.ajax({
			url: "sys.php",
			method: "POST",
			data: { insert : feel },
			dataType: "html"
		});
		 
		request.done(function( list ) {
			console.log(list);
			GetRelatedFeelings(feel);
			GetLatestFeelings();
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			console.log("Failed to register the feel");
		});
		$('#feeling-insert').val("");
		$("#suggestion-container").hide();
	}
}

function UpdateSuggestions() {
	var inp = $('#feeling-insert').val();
	var request = $.ajax({
		url: "sys.php",
		method: "POST",
		data: { input : inp },
		dataType: "html"
	});
	 
	request.done(function( list ) {
	
		$( "#suggestions ul" ).html( list );
		if(list != "")
			$("#suggestion-container").css("display", "inline-block");
		else 
			$("#suggestion-container").hide();
	});
	 
	request.fail(function( jqXHR, textStatus ) {
		$("#suggestion-container").hide();
	});
}

function GetLatestFeelings() {
	var request = $.ajax({
		url: "sys.php",
		method: "POST",
		data: { retr : 1 },
		dataType: "html"
	});
	 
	request.done(function( list ) {
		if($(".latest-container").length) {
			$(".latest-container").html(list);
		} else {
			$( ".feelings-container" ).append('<div class="latest-container">' + list + '</div>' );
		}
	});
	 
	request.fail(function( jqXHR, textStatus ) {
		$( ".feelings-container" ).append( "Can't load latest feelings :(" );
	});
}
