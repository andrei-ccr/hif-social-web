$(document).ready(function() {
	
	if(IsDiscussionPage()) {
		// If the current page is the discussion page
		$(".feel").css("cursor", "auto");
		$(".feel").hover(function() { $(this).css("border-color", "rgba(0,0,0,0.2)"); });
		$(".feel-txt").css("cursor", "pointer");
	}
	
	$(document).on('click', function() {
		$("#suggestion-container").hide();
	});
		
	$(document).on('keypress', function(e) {
		if(e.which == 13) {
			if($('#comment-insert').length) {
				ProccessComment();
			} else if($('#feeling-insert').length) {
				ProccessFeeling();
			}
		}
	});
	
	$("#feeling-insert").on('click', function(e) {
		e.stopPropagation(); //Prevent propagation to the document so the suggestions don't get hidden.
		return false;
	});
	
	//Writing a feel
	$('#feeling-insert').on('input', function(e) {
		var inp = $(this).val();
		if(inp.length <=0) {
			$("#suggestion-container").hide();
		} else {
			UpdateSuggestions();
		}
	});
	
	//Clicking on a suggestion
	$("#suggestion-container #suggestions ul").on('click', 'li', function(e) {
		$('#feeling-insert').val($(this).text());
		$("#suggestion-container").hide();
		e.stopPropagation(); 
		return false;
	});
	
	
	//Clicking on a feeling
	$('.feelings-container').on('click', '.feel', function(e) {
		if(!IsDiscussionPage()) {
			window.location.href = "discussion.php?id=" + $(this).data("id"); 
		}
	});
	
	//Clicking on a feel
	$('.feelings-container').on('click', '.feel-txt', function(e) {
		e.stopPropagation();
		GetRelatedFeelings($(this).text());
	});
	
	//Clicking on submit button for feelings
	$('#feeling-submit').on('click',ProccessFeeling);
	
	//Clicking on submit button for comments
	$('#comment-submit').on('click', ProccessComment);

	$('#header-login-btn').on('click', function() {
		var request = $.ajax({
			url: "account_action.php",
			method: "POST",
			data: { username : "Jerry" , password : "1234" },
			dataType: "html"
		});
		 
		request.done(function( out ) {
			console.log(out);
			location.reload();
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			console.log("Failed to login");
		});

	});

	$('#header-logout-btn').on('click', function() {
		var request = $.ajax({
			url: "account_action.php",
			method: "POST",
			data: { logout: 1 },
			dataType: "html"
		});
		 
		request.done(function( out ) {
			console.log(out);
			location.reload();
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			console.log("Failed to login");
		});

	});
	
});