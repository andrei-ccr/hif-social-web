function IsDiscussionPage() {
	if($(".f-c-discussion").length) return true;
	else return false;
}

function GetRelatedFeelings(fid) {
	if(!IsDiscussionPage()) {
		var request = $.ajax({
			url: "sys.php",
			method: "POST",
			data: { feeling_id : fid },
			dataType: "html"
		});
		 
		request.done(function( list ) {
			if($(".f-related-container").length) {
				$(".f-related-container").html(list);
			} else {
				$( ".feelings-container" ).prepend('<div class="f-related-container">' + list + '</div>');
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
		var fid = $('.nfeel').data("id"); //There will be only 1 feel so it's safe to do this, but should find a better way.
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
		if($(".f-latest-container").length) {
			$(".f-latest-container").html(list);
		} else {
			$( ".feelings-container" ).append('<div class="f-latest-container">' + list + '</div>' );
		}
	});
	 
	request.fail(function( jqXHR, textStatus ) {
		$( ".feelings-container" ).append( "Can't load latest feelings :(" );
	});
}
