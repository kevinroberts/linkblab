// Jquery Extentions
(function($){
    var _old = $.unique;
    $.unique = function(arr){
        // do the default behavior only if we got an array of elements
        if (!!arr[0].nodeType){
            return _old.apply(this,arguments);
        } else {
            // reduce the array to contain no dupes via grep/inArray
            return $.grep(arr,function(v,k){
                return $.inArray(v,arr) === k;
            });
        }
    };
})(jQuery);
jQuery.log = function (msg) {
	if( window.console )
	 console.log("%s", msg);
      return this;
};
// --------END Jquery Extensions---------- //

function checkIfVoted(elm, type, isLink) {
	  if (type == 1){
		  if (isLink)
			  var scoreElm = elm.next("div").next("div");
		  else
			  var scoreElm = elm.parent(".midcol").next(".entry").children(".noncollapsed").children(".tagline").children(".total");
		  if (scoreElm.hasClass("unVoted")) {
			  return true;
		  }
		  else if (scoreElm.hasClass("downvoted")){
			return true;  
		  }
		  else
			  return false;
	  }
	  else {
		  if (isLink)
			  var scoreElm = elm.prev("div").prev("div");
		  else
			  var scoreElm = elm.parent(".midcol").next(".entry").children(".noncollapsed").children(".tagline").children(".total");
		  if (scoreElm.hasClass("unVoted")) {
			  return true;
		  }
		  else if (scoreElm.hasClass("voted")){
				return true;  
			  }
			  else
				  return false;
	  }
  }
  
function swapVoteClass(elm, type) {
	  // swap relative to the vote anchor
	  if (type == 1){
		  var scoreElm = elm.next("div").next("div");
		  scoreElm.removeClass("unVoted");
		  scoreElm.addClass("voted");
		  scoreElm.removeClass("downvoted");
		  if (!(scoreElm.hasClass("unVoted"))) {
			  scoreElm.css("color", "orangered");
		  }
		  elm.children("span").addClass("voted");
		  elm.children("span").removeClass("downvoted");
	  }
	  else {
		  var scoreElm = elm.prev("div").prev("div");
		  scoreElm.removeClass("unVoted");
		  if (!(scoreElm.hasClass("unVoted"))) {
			  scoreElm.css("color", "#9494FF");
		  }
		  scoreElm.addClass("downvoted");
		  scoreElm.removeClass("voted");
		  elm.children("span").addClass("downvoted");
		  elm.children("span").removeClass("voted");
	  }
  }
  
function swapVoteClassComment(elm, type) {
	  // swap relative to the vote anchor
	  var scoreElm = elm.parent(".midcol").next(".entry").children(".noncollapsed").children(".tagline").children(".total");
	  if (type == 1){
		  scoreElm.removeClass("unVoted");
		  scoreElm.addClass("voted");
		  scoreElm.removeClass("downvoted");
		  if (!(scoreElm.hasClass("unVoted"))) {
			  //scoreElm.css("color", "orangered");
		  }
		  elm.children("span").addClass("voted");
		  elm.children("span").removeClass("downvoted");
	  }
	  else {
		  scoreElm.removeClass("unVoted");
		  if (!(scoreElm.hasClass("unVoted"))) {
			  //scoreElm.css("color", "#9494FF");
		  }
		  scoreElm.addClass("downvoted");
		  scoreElm.removeClass("voted");
		  elm.children("span").addClass("downvoted");
		  elm.children("span").removeClass("voted");
	  }
  }
  
function animateVote(elm, type, points) {
	  if (type == 1){
		  var scoreElm = elm.next("div").next("div");
		  scoreElm.html( points.toString());
		  scoreElm.effect("highlight", {color:'#53ff7b'}, 500); 
	  }
	  else {
		  var scoreElm = elm.prev("div").prev("div");
		  scoreElm.html( points.toString());
		  scoreElm.effect("highlight", {color:'#ff3a3a'}, 500); 
	  }
  }
  
function animateVoteComment(elm, type, points) {
	  var collapsedScore = elm.parent(".tagline").parent(".noncollapsed").prev(".collapsed").children(".total");
	  if (points > 1 || points < 0)
		  var pnt = " points";
	  else
		  var pnt = " point";
	  if (type == 1){
		  elm.html( points.toString() + pnt);
		  collapsedScore.html( points.toString() + pnt);
		  elm.effect("highlight", {color:'#53ff7b'}, 500); 
	  }
	  else {
		  elm.html( points.toString() + pnt);
		  collapsedScore.html( points.toString() + pnt);
		  elm.effect("highlight", {color:'#ff3a3a'}, 500); 
	  }
	  
  }
  
function isLoggedIn() {
	  var loginTxt = $("#loginLink").attr( "name" );
	  if (loginTxt == 'logged_out')
		return false;
	  else
		return true; 
  }
  
function commentVoteAction(elm, type, number) {
	  if (!isLoggedIn()) {
		  showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		  return false;
	  }
	  var scoreElm = elm.parent(".midcol").next(".entry").children(".noncollapsed").children(".tagline").children(".total");
	  if (type == 1) {
		// this is an up vote:
		// check if this comment has not been up-voted yet
		  if (checkIfVoted(elm, type, false)) {
				$.ajax( {
					type : "POST",
					url : "/blabs/vote",
					data : "comment="+number+"&type="+type,
					success : function(data) {
					var response = jQuery.parseJSON( data );
	    			if (response.success == true)
	    			{
	    				  swapVoteClassComment(elm, type);
	    				  // check if the down vote link needs its class removed:
	    				  var el2 = $("#com-" + number + "-down");
	    					if (el2.children("span").hasClass("downvoted")) {
	    						el2.children("span").removeClass("downvoted");
	    					}
	    				  var downPoints = scoreElm.prev("span").prev("span").text();
	    				  downPoints = parseInt(downPoints);
	    				  
	    				  var upPoints = scoreElm.prev("span").text();
	    				  upPoints = parseInt(upPoints);
	    				  upPoints++;
	    				  // update up votes with new amount
	    				  scoreElm.prev("span").text(upPoints.toString());
	    				  
	    				  var points = upPoints - downPoints;

	    				  // Animate the upvote.:
	    				  animateVoteComment(scoreElm, type, points);
	    			}
	    			else if (response.error == 'login')
	    			{
	    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
	    			}
	    			else {
	    				alert("Vote Failure : " + response.message);    	
	    			}
					}
				
				});
		  }
	  }
	  else {
		    // else this is a down vote
			// check if this link has not been down voted on yet
			if (checkIfVoted(elm, type, false)) {
				$.ajax( {
					type : "POST",
					url : "/blabs/vote",
					data : "comment="+number+"&type="+type,
					success : function(data) {
					var response = jQuery.parseJSON( data );
	    			if (response.success == true)
	    			{
	    				  swapVoteClassComment(elm, type);
	    				  // check if the up vote link needs its class removed:
	    				  var el2 = $("#com-" + number + "-up");
	    					if (el2.children("span").hasClass("voted")) {
	    						el2.children("span").removeClass("voted");
	    					}
	    				  
	    				  var upPoints = scoreElm.prev("span").text();
	    				  upPoints = parseInt(upPoints);
	    				  
	    				  var downPoints = scoreElm.prev("span").prev("span").text();
	    				  downPoints = parseInt(downPoints); downPoints++;
	    				  // update number of down votes with new amount
	    				  scoreElm.prev("span").prev("span").text(downPoints.toString());
	    				  
	    				  var points = upPoints - downPoints;
	    				  
	    				  // Animate the downvote.:
	    				  animateVoteComment(scoreElm, type, points);
	    			}
	    			else if (response.error == 'login')
	    			{
	    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
	    			}
	    			else {
	    				alert("Vote Failure : " + response.message);
	    			}
					}
				
				});
				
			}
		  
	  }
	  
	  return false;
  }
  
function voteAction(elm, type, number) {
	   
	  if (!isLoggedIn()) {
		  showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		  return false;
	  }
	   
	   if(type == 1) {
		    // this is an up vote:
		    // check if this link has not been up-voted yet
			if (checkIfVoted(elm, type, true)) {
			  
				$.ajax( {
					type : "POST",
					url : "/blabs/vote",
					data : "link="+number+"&type="+type,
					success : function(data) {
					var response = jQuery.parseJSON( data );
	    			if (response.success == true)
	    			{
	    				  swapVoteClass(elm, type);
	    				  // check if the down vote link needs its class removed:
	    				  var el2 = $("#link" + number + "-down");
	    					if (el2.children("span").hasClass("downvoted")) {
	    						el2.children("span").removeClass("downvoted");
	    					}
	    				  var downPoints = elm.next("div").text();
	    				  downPoints = parseInt(downPoints);
	    				  
	    				  var upPoints = elm.next("div").next("div").next("div").text();
	    				  upPoints = parseInt(upPoints); upPoints++;
	    				  elm.next("div").next("div").next("div").text(upPoints.toString());
	    				  
	    				  var points = upPoints - downPoints;

	    				  // Animate the upvote.:
	    				  animateVote(elm, type, points);
	    			}
	    			else if (response.error == 'login')
	    			{
	    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
	    			}
	    			else {
	    				alert("Vote Failure : " + response.message);    	
	    			}
					}
				
				});
			  
			}
			else
			return false;
		}
		else
		{
		 // else this is a down vote
			// check if this link has not been down voted on yet
			if (checkIfVoted(elm, type, true)) {
					$.ajax( {
						type : "POST",
						url : "/blabs/vote",
						data : "link="+number+"&type="+type,
						success : function(data) {
						var response = jQuery.parseJSON( data );
		    			if (response.success == true)
		    			{
		    				  swapVoteClass(elm, type);
		    				  // check if the up vote link needs its class removed:
		    				  var el2 = $("#link" + number + "-up");
		    					if (el2.children("span").hasClass("voted")) {
		    						el2.children("span").removeClass("voted");
		    					}
		    				  
		    				  var upPoints = elm.prev(".upVotes").text();
		    				  upPoints = parseInt(upPoints);
		    				  
		    				  var downPoints = elm.prev("div").prev("div").prev("div").text(); 
		    				  downPoints = parseInt(downPoints); downPoints++;
		    				  elm.prev("div").prev("div").prev("div").text(downPoints.toString());
		    				  
		    				  var points = upPoints - downPoints;
		    				  
		    				  // Animate the downvote.:
		    				  animateVote(elm, type, points);
		    			}
		    			else if (response.error == 'login')
		    			{
		    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		    			}
		    			else {
		    				alert("Vote Failure: " + response.message);
		    			}
						}
					
					});
				
				  
			}
			else
			return false;

		}
		
		return false;
	 
		 
	  
  }

function recentVoteAction(elm, type, number) {
	 
	if (!isLoggedIn()) {
		  showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		  return false;
	  }
	
	var points = $("#recent-link"+number+"-points").attr('title');
	points = parseInt(points);
	
	if(type == 1)
	{
	    if (!elm.children("span").hasClass("voted")) {
	    	
			$.ajax( {
				type : "POST",
				url : "/blabs/vote",
				data : "link="+number+"&type="+type,
				success : function(data) {
				var response = jQuery.parseJSON( data );
    			if (response.success == true)
    			{
    				elm.children("span").addClass("voted");	
    				points++;
    				$("#recent-link"+number+"-points").html( points.toString() + " points" );
    				$("#recent-link"+number+"-points").effect("highlight", {color:'#53ff7b'}, 1000);
    				
    				var el2 = $("#recent-link" + number + "-down");
    				if (el2.children("span").hasClass("downvoted")) {
    					el2.children("span").removeClass("downvoted");
    				}
    			}
    			else if (response.error == 'login')
    			{
    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
    			}
    			else {
    				alert("Vote Failure: " + response.message);    	
    			}
				}
			
			});
	    }
		
	}
	else
	{
		if (!elm.children("span").hasClass("downvoted")) {
			
			$.ajax( {
				type : "POST",
				url : "/blabs/vote",
				data : "link="+number+"&type="+type,
				success : function(data) {
				var response = jQuery.parseJSON( data );
    			if (response.success == true)
    			{
    				elm.children("span").addClass("downvoted");	
    				points--;
    				$("#recent-link"+number+"-points").html( points.toString() + " points" );
    				$("#recent-link"+number+"-points").effect("highlight", {color:'#ff3a3a'}, 1000);
    				var el2 = $("#recent-link" + number + "-up");
    				if (el2.children("span").hasClass("voted")) {
    					el2.children("span").removeClass("voted");
    					}	
    			}
    			else if (response.error == 'login')
    			{
    				showLogin('You must be logged in to vote. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
    			}
    			else {
    				alert("Vote Failure: " + response.message);
    			}
				}
			
			});
		}

	}

}
  
function hideComment(elm) {
	  var noncollapsedDiv = elm.parent().parent(".noncollapsed");
	  var collapsedDiv = noncollapsedDiv.prev(".collapsed");
	  var voteBtns = collapsedDiv.parent().prev(".midcol");
	  // Hide all child comments
	  var childComments = voteBtns.parent(".comment").next().next(".child");
	  noncollapsedDiv.hide();
	  voteBtns.hide();
	  childComments.hide();
	  collapsedDiv.show();
	  return false;
  }
  
function showComment(elm) {
	 var collapsedDiv = elm.parent(".collapsed");
	 collapsedDiv.hide();
	 var voteBtns = collapsedDiv.parent().prev(".midcol");
	 voteBtns.show();
	 var uncollapsedDiv = collapsedDiv.next();
	 uncollapsedDiv.show();
	 // Show all child comments
	 var childComments = voteBtns.parent(".comment").next().next(".child");
	 childComments.show();
	 return false;
 }
 
function showLogin(msg) {
	 	if (msg != null || msg != '') {
			$('#dynamicLoginError').html(msg);
	 	}
		$('#dynamicLogin').dialog('open');
		$('#dynUsername').focus();
		return false;
 }
 
function toggle_delete(ele, commentID) {
	 if ($("#dialog-confirm").length == 0) {
	 $("#dynamicLogin").after('<div id="dialog-confirm" title="Delete your comment?"> <p> <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>	Your comment will be permanently deleted; Are you sure?	</p>	</div>	 ');
	 }
	 var comment = $("#comment-"+commentID);
		$( "#dialog-confirm" ).dialog({
			resizable: true,
			width: 430,
			modal: true,
			buttons: {
				"Delete Comment": function() {
					$( this ).dialog( "close" );
					comment.fadeOut('slow', function() {
						comment.remove();
						  });
		    		$.ajax( {
						type : "POST",
						url : "/blabs/comment",
						data : "type=delete&commentID="+commentID,
						success : function(data) {
						var response = jQuery.parseJSON( data );
		    			if (response.success == true)
		    			{
		    			}
		    			else if (response.error == 'login')
		    			{
		    				showLogin('You must be logged in to delete. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		    			}
		    			else {
		    			}
						}
					
					});
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
		return false;
	 
 }
 
function toggle_edit(ele, commentID) {
	 var editForm = $('#formEdit-'+commentID);
	 if (editForm.hasClass("closed")) {
		 editForm.removeClass("closed");
		 editForm.show();
		 ele.hide();
	 }
	 else {
		editForm.addClass("closed");
		editForm.hide();
		$('.edit-usertext').show();

	 }
	 return false;
}
 
function toggle_reply(ele, commentID) {
	 var replyForm = $('#formReply-'+commentID); 
	 if (replyForm.hasClass("closed")) {
		 replyForm.removeClass("closed");
		 replyForm.show();
		 replyForm.find("textarea").focus();
		 ele.hide();
	 }
	 else {
		replyForm.addClass("closed");
		replyForm.hide();
		$('.reply-usertext').show();

	 }
	 return false;
 }
 
function post_comment(comForm, type) {
	 var err = comForm.find(".form_errors");
	 var status = comForm.find(".status"); 
	 var sBtn = comForm.find("button");
	 var valid = true;
	 var link_id = 0;
	 err.hide();
	 // If this a new parent comment
	 if (type == 'parent' || type == "reply") {
		 	  var values = comForm.serializeArray();
		      jQuery.each(values, function(i, field){
		          if (field.name == "text") {
		        	  if (field.value == '') {
		        		  err.text('Woah there buddy! Enter some text first');
		        		  err.show(); 
		        		  valid = false;
		        	  }
		        	  if (field.value.length > 10000) {
		        		  err.text('Your comment is too long. Keep it under 10,000 characters.');
		        		  err.show(); 
		        		  valid = false;
		        	  }
		        	 
		          }
		          if (field.name == "link_id" || field.name == "comment_id") {
		        	  if (field.value == '' || isNaN(field.value) ) {
		        		  err.text('error! there was a problem processing your request');
		        		  err.show();
		        		  valid = false;
		        	  }
		        	  if (field.name == "link_id")
		        		  link_id = field.value;
		          }
		        });
		      // Comment valid -- now post it
		      if (valid) {
		    	  status.show();
		    	  sBtn.attr('disabled', '1'); // disable the submit button until ajax is finished
					$.ajax( {
						type : "POST",
						url : "/blabs/comment",
						data : comForm.serialize() + "&type="+type,
						success : function(data) {
						var response = jQuery.parseJSON( data );
		    			if (response.success == true)
		    			{
							// hide no comments message if exists
							if ($("#noComments").length > 0)
								$("#noComments").hide();
		    				err.text(response.message);
		    				err.show();
		    				var texarea = comForm.find("textarea");
		    				var username = $("#loginLink").text();
		    				// Insert this new comment on top of all other comments:
		    				$child1 = ''; $child2 = '';
		    				if (type == "reply") {
		    					$child1 = "<div style=\"padding-top: 10px; border-left: 1px dotted #DDF;\"> ";
		    					$child2 = " </div>";
		    				}
		    				comForm.after($child1+'<div class="comment" id="comment-'+response.commentID+'">'+
		    				'<div class="midcol" style="display: block;">'+
		    				'<a id="com-'+response.commentID+'-up" class="ui-state-default ui-corner-all" title="vote this comment up" onclick="commentVoteAction($(this), 1, '+response.commentID+')"><span class="ui-icon ui-icon-circle-arrow-n voted"></span></a>'+
		    				'<a id="com-'+response.commentID+'-down" class="ui-state-default ui-corner-all" title="vote this comment down" onclick="commentVoteAction($(this), 2, '+response.commentID+')"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>'+
		    				'</div>'+
		    				'<div class="entry">'+
		    				'<div class="collapsed" style="display: none;">'+
		    				'<a href="/user/'+username+'" class="author">'+username+'</a>&nbsp;'+
		    				'<span class="score downVotes">0</span>'+
		    				'<span class="score upVotes">1</span>'+
		    				'<span class="score total voted">1 point</span>'+
		    				'&nbsp;1 second ago&nbsp;'+
		    				'<a href="#" class="expand" onclick="return showComment($(this))" title="expand">[+] (0 children)</a>'+
		    				'</div> <div class="noncollapsed" style="display: block;">'+
		    				'<p class="tagline">'+
		    				'<a href="/user/'+username+'" class="author">'+username+'</a>&nbsp;'+
		    				'<span class="score downVotes">0</span>'+
		    				'<span class="score upVotes">1</span>'+
		    				'<span class="score total voted">1 point</span>'+
		    				'&nbsp;1 second ago&nbsp;'+
		    				'<a href="#" class="expand" onclick="return hideComment($(this))" title="collapse">[-]</a>'+
		    				'</p>'+
		    				'<div class="md">'+
		    				'<div>'+
		    				response.comment+
		    				'</div>'+
		    				'</div>'+
		    				'<form style="display:none;" action="" class="closed cloneable" onsubmit="return post_comment($(this), \'edit\')" method="post" name="newCommentForm" id="formEdit-'+response.commentID+'">'+
		    				'<div class="usertext usertext-comment-edit">'+
		    				'<div><textarea name="text">'+
		    				texarea.val()+
		    				'</textarea>'+
		    				'</div>'+
		    				'<div class="form_errors" style="display: none;"></div>'+
		    				'<div class="bottom-area">'+ 
		    				'<div class="usertext-buttons">'+
		    				'<input type="hidden" name="commentID" value="'+response.commentID+'">'+
		    				'<button type="submit" class="save">save</button>' +
		    				'<button class="cancel" onclick="return toggle_edit($(this), '+response.commentID+')" type="button">cancel</button>' +
		    				'<span style="display: none;" class="status">submitting...</span>' +
		    				'</div>' +
		    				'</div>' +
		    				'</div>' +
		    				'</form>' +
		    				'<ul class="flat-list buttons">'+
		    				'<li class="first"><a href="/b/self/comment/'+response.commentID+'" class="bylink" rel="nofollow">permalink</a></li>'+
		    				'<li><a class="edit-usertext" onclick="return toggle_edit($(this), '+response.commentID+')" href="#">edit</a></li>'+
		    				'<li><a class="delete-usertext" onclick="return toggle_delete($(this), '+response.commentID+')" href="#">delete</a></li>'+
		    				'<li class="first"><a class="reply-usertext" title="reply to this comment" onclick="return toggle_reply($(this), '+response.commentID+')" href="#">reply</a></li>'+
		    				'<form style="display:none;" action="" class="closed cloneable" onsubmit="return post_comment($(this), \'reply\')" method="post" name="newCommentForm" id="formReply-'+response.commentID+'">'+
		    				'<div class="usertext usertext-comment-reply">'+
		    				'<div><textarea name="text"></textarea></div>'+
		    				'<div class="form_errors" style="display: none;"></div>'+
		    				'<div class="bottom-area">'+
		    				'<div class="usertext-buttons">'+
		    				'<input type="hidden" name="comment_id" value="'+response.commentID+'">'+
		    				'<input type="hidden" name="link_id" value="'+link_id+'">'+
		    				'<button type="submit" class="save">save</button>'+
		    				'<button class="cancel" onclick="return toggle_reply($(this), '+response.commentID+')" type="button">cancel</button>'+
		    				'<span style="display: none;" class="status">submitting...</span>'+
		    				'</div> </div> </div> </form>'+
		    				'</ul>'+
		    				'</div>'+
		    				'</div>'+
		    				'</div>'+$child2);
		    				texarea.val('');
		    				if (type == "reply") {
		    					comForm.hide();
		    					comForm.addClass('closed');
		    				}
		    			}
		    			else if (response.error == 'login')
		    			{
		    				showLogin('You must be logged in to comment. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		    			}
		    			else {
		    				err.text("Error: " + response.message);
		    				err.show();
		    			}
		    			status.hide();
		    			sBtn.removeAttr('disabled');
						}
					
					});
		    	  
		    	  
		      }
		 		 
	 }
	 else if (type == 'edit') {
		 var values = comForm.serializeArray();
	      jQuery.each(values, function(i, field){
	          if (field.name == "text") {
	        	  if (field.value == '') {
	        		  err.text('Woah there buddy! Enter some text first');
	        		  err.show(); 
	        		  valid = false;
	        	  }	        	  
	        	  if (field.value.length > 10000) {
	        		  err.text('Your comment is too long. Keep it under 10,000 characters.');
	        		  err.show(); 
	        		  valid = false;
	        	  }
	        	 
	          }
	          if (field.name == "commentID") {
	        	  if (field.value == '' || isNaN(field.value) ) {
	        		  err.text('Invalid comment. Please Try again');
	        		  err.show(); 
	        		  valid = false;
	        	  }
	          }
	        });
	      // Comment valid -- now post it
	      if (valid) {
	    	  status.show();
	    	  sBtn.attr('disabled', '1'); // disable the submit button until ajax is finished
	    		$.ajax( {
					type : "POST",
					url : "/blabs/comment",
					data : comForm.serialize() + "&type="+type,
					success : function(data) {
					var response = jQuery.parseJSON( data );
	    			if (response.success == true)
	    			{
	    				err.text(response.message);
	    				err.show();
	    				var texarea = comForm.find("textarea");
	    				var oldText = comForm.prev(".md");
	    				var username = $("#loginLink").text();
	    				// Insert this new comment on top of all other comments:
	    				//comForm.after();
	    				oldText.html(response.comment); // add the decoda encoded result
	    				toggle_edit(comForm, response.commentID);
	    			}
	    			else if (response.error == 'login')
	    			{
	    				showLogin('You must be logged in to comment. <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
	    			}
	    			else {
	    				err.text("Error: " + response.message);
	    				err.show();
	    			}
	    			status.hide();
	    			sBtn.removeAttr('disabled');
					}
				
				});
	      }
	 }

	 return false;
	 
	 
 }
 
function hideForm (ele) {
	 var form = ele.nextAll("form");
	 if (ele.hasClass( "collapsedForm" )) {
		 form.slideDown();
		 ele.text('[- Add Comment]');
		 ele.removeClass("collapsedForm");
	 }
	 else {
		 form.slideUp();
		 ele.text('[+ Add Comment]');
		 ele.addClass("collapsedForm");
	 }
	 ele.blur();
	 return false;
 }

function linkBlab_click(event, ele) {
	//if(event.preventDefault) { 
	  //event.preventDefault(); 
	//	}	else
	  //event.returnValue = false;
	 
	var l = ele.attr("href");
	var linkID = ele.attr("name").replace("linkblab_link", "").replace("-", "");
	var links = $.cookie("rec_links");
	if (links == null) {
		$.cookie('rec_links', linkID, { expires: 7, path: '/', domain: 'linkblab.com' });
	}
	else {
		links = linkID + ';' + unescape(links);
	    links = $.unique(links.split(';')); // convert to array and remove dupes
	    
	    if (links.length > 5) { // remove links after 5
	    	links.splice(5, links.length - 5);
	    }
	    links = links.join(';');
		$.cookie('rec_links', links, { expires: 7, path: '/', domain: 'linkblab.com' });
	}
	
	 // window.setTimeout(function () {
		  //  window.location.href = l;
		//	},100);
	
	return event.returnValue;
}

function clearHistory() {
	$('.centerHeader').hide();
	$('#recentlyViewed').fadeOut();
	$.cookie('rec_links', null, { path: '/', domain: 'linkblab.com' });
	return false;
}

$(document).ready(function() {
	 
	  $('#search').defaultValue({'value':' Search Linkblab'});

	  $(".linkblab_link").click(function(event) {
		  return linkBlab_click(event, $(this));
		});
	  
	  $(".decoda-spoilerBody-blk").click(function(event) {
		  $.log("Spoiler Clicked");
		  
		  if(event.preventDefault) { 
			  event.preventDefault(); }
		  else
			  event.returnValue = false;
		  return false;
		});

		$("#sortOptionsDropdown").linkselect({
			change: function(li, value, text){
				var g = value.split("|");
				if (g[0] == 'index') {
					var url = "/index/" + g[1];
					window.location.href = url;
				}
				else if (g[0].indexOf("domain/") !== -1) {
					var url = "/" + g[0] + "/" + g[1];
					window.location.href = url;
				}
				else {
					var url = "/b/" + g[0] + "/" + g[1];
					window.location.href = url;
					
				}
	  		}
		});
		
		$("#topSortOptionsDropdown").linkselect({
			change: function(li, value, text){
				var g = value.split("|");
				if (g[0] == 'index') {
					var url = "/index/" + g[1];
					window.location.href = url;
				}
				else {
					var url = "/b/" + g[0] + "/" + g[1];
					window.location.href = url;
					
				}
	  		}
		});
		
		
		
		$("#commentSortOptionsDropdown").linkselect({
			change: function(li, value, text){
				var g = value.split("|");
				var url = unescape(g[0]) + '/' + g[1] + '#commentsArea';
			    window.location.href = url;
	  		}
		});
		$('input#title, input#link_url').focus(function() {
			if ($(this).css( 'width') !== '510px') {  //toggle this only once
			$(this).animate({
				    width: 510
				    }, 500, function() {
				    // Animation complete.
				  });
			}

		});
		
	  	$('.viewedVote a').hover(
			   function() { $(this).addClass('ui-state-hover'); },
			   function() { $(this).removeClass('ui-state-hover'); }
			   );
	  	$('.midcol a').hover(
				   function() { $(this).addClass('ui-state-hover'); },
				   function() { $(this).removeClass('ui-state-hover'); }
				   );
		$('.removebtn').hover(
				function() { $(this).children('span').removeClass('ui-icon-minus'); $(this).children('span').addClass('ui-icon-circle-minus');  },
				function() { $(this).children('span').removeClass(' ui-icon-circle-minus'); $(this).children('span').addClass('ui-icon-minus'); }
			);
	  	
		$('.addbtn').hover(
				function() { $(this).children('span').removeClass('ui-icon-plus'); $(this).children('span').addClass('ui-icon-circle-plus');  },
				function() { $(this).children('span').removeClass(' ui-icon-circle-plus'); $(this).children('span').addClass('ui-icon-plus'); }
			);
		
		$(".usertext-edit textarea").resizable();
		
		var username = $( "#dynUsername" ),
		password = $( "#dynPassword" ),
		allFields = $( [] ).add( username ).add( password ),
		usernameMsg = $("#usernameError"),
		passwordMsg = $("#passwordError");
		
		$("#dynUsername, #dynPassword").bind('keypress', function(e) {
			  var code = (e.keyCode ? e.keyCode : e.which);
			  if(code == 13) { //Enter keycode
			     $("#dynamicLogin").nextAll(".ui-dialog-buttonpane").children('div').children('button:first').click();
			  }

		  });
		
		
		$("#dynamicLogin").dialog({
			autoOpen: false,
			height: 270,
			width: 490,
			modal: true,
			buttons: {
				'Login': function() {
					allFields.removeClass( "ui-state-error" ); passwordMsg.text("");usernameMsg.text("");
					var rememberMe = $("#rememberMe:checked");
					if (rememberMe.val() !== null) {
					 	rememberMe = 1;
					}
					else {
					   rememberMe = 0;
					}
					var bValid = true;
					var token = $('#token').val();
					if (username.val().length == 0)
					{
						username.addClass("ui-state-error");
						usernameMsg.text("* required");
						username.focus();
						bValid = false;
					}
					if (password.val().length == 0 || token.length == 0)
					{
						password.addClass("ui-state-error");
						passwordMsg.text("* required");
						bValid = false;
					}
					if (!bValid) {
						$(this).dialog('option', "width", 550);
					}
					if (bValid) {

						$.ajax( {
							type : "POST",
							url : "/auth/login",
							data : "username="+username.val()+"&password="+password.val()+"&token="+token+"&pl="+rememberMe,
							beforeSend: function(request) {
							$('#resetemail').attr('enabled', 'false');
							$('#dynamicLoginForm').hide();
							$('#ajaxLoader').show();
							},
							success : function(data) {
							$('#resetemail').attr('enabled', 'true');
							$('#ajaxLoader').hide();
							$('#dynamicLoginForm').show();
							var response = jQuery.parseJSON( data );
			    			if (response.isvalid == 'true')
			    			{
			    			$('#dynamicLogin').dialog('close');
			    			location.reload(true);
			    			
			    			}
			    			else
			    			{
			    				$('#dynamicLoginError').text("Error: " + response.message);
			    				$(this).dialog('option', "height", 400);
			    			}
							}
						
						});
						
					   
						
					}
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error'); passwordMsg.text(""); usernameMsg.text(""); $('#dynamicLoginError').text("");
			}
		});
				
});