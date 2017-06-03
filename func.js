$(document).ready(function() {
	//GetLatestFeelings();
	
	$(document).click(function() {
		$("#suggestion-container").hide();
	});
	$("#feeling-insert").click(function(e) {
		e.stopPropagation(); 
		return false;
	});
	$("#suggestion-container #suggestions ul").on('click', 'li', function(e) {
		
		$('#feeling-insert').val($(this).text());
		$("#suggestion-container").hide();
		e.stopPropagation(); 
		return false;
	});
	
	$('#feeling-insert').on('input', function(e) {
		var inp = $(this).val();
		//console.log(inp);
		if(inp.length <=0) {
			$("#suggestion-container").hide();
		} else {
			UpdateSuggestions();
		}
	});
	
	$('.feelings-container').on('click', '.feel', function(e) {
		window.location.href = "discussion.php?id=" + $(this).data("id"); 
	});
	
	
	$('#feeling-submit').click(function(e) {
		ProccessFeeling();
	});
	
	$('#feeling-submit').keypress(function(e) {
		if(e.which == 13) {
			ProccessFeeling();
		}
	});
	
	$('#comment-submit').on('click', function(e) {
		ProccessComment();
	});
	$('#comment-submit').on('keypress', function(e) {
		if(e.which == 13) {
			ProccessComment();
		}
	});
	
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
		
			$( ".feelings-container" ).html( list );
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			$( ".feelings-container" ).html( "Can't load latest feelings :(" );
		});
	}
	
});