function addClass(ele,cls) {
		if (!this.hasClass(ele,cls)) ele.className += " "+cls;
	}

	function removeClass(ele,cls) {
		if (hasClass(ele,cls)) {
	    	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
			ele.className=ele.className.replace(reg,' ');
		}
	}
	
  function toggleClassname (el, newClassname, defaultClassname) {

    if (hasClass( el, defaultClassname)){
      var re = new RegExp("(^|\\s)" + defaultClassname + "(\\s|$)"); 
      el.className = el.className.replace(re, ' '+ newClassname +' ');

    } else if (hasClass( el, newClassname)){
      var re = new RegExp("(^|\\s)" + newClassname + "(\\s|$)"); 
      el.className = el.className.replace(re, ' '+ defaultClassname +' ');

    } else
      el.className += ' ' + newClassname;

  }

  function hasClass (obj, className) {

	  if (typeof obj == 'undefined' || obj==null || !RegExp) { 
	    return false; 
	  }

	  var re = new RegExp("(^|\\s)" + className + "(\\s|$)");
	  if (typeof(obj)=="string") {
	    return re.test(obj);
	  }
	  else if (typeof(obj)=="object" && obj.className) {
	    return re.test(obj.className);
	  }
	  return false;
	}
	
  
  function addClass(element, value) {
		if(!element.className) {
			element.className = value;
		} else {
			newClassName = element.className;
			newClassName+= " ";
			newClassName+= value;
			element.className = newClassName;
		}
	}
  
  function checkIfVoted(elm, type) {
	  if (type == 1){
		  var scoreElm = elm.next("div").next("div");
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
		  var scoreElm = elm.prev("div").prev("div");
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
  
  function isLoggedIn() {
	  var loginTxt = $("#loginLink").attr( "name" );
	  if (loginTxt == 'logged_out')
		return false;
	  else
		return true; 
  }
  
  function voteAction(elm, type, number) {
	   
	  if (!isLoggedIn()) {
		  showLogin('You must be logged in to vote! <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		  return false;
	  }
	   
	   if(type == 1) 
		{
	  // this is an up vote:
		    // check if this link has not been up voted on yet
			if (checkIfVoted(elm, type)) {
			  
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

	    				  // Animate the upVote!:
	    				  animateVote(elm, type, points);
	    			}
	    			else if (response.error == 'login')
	    			{
	    				showLogin('You must be logged in to vote! <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
	    			}
	    			else {
	    				alert("Server AJAX error : " + response.message);    	
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
			if (checkIfVoted(elm, type)) {
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
		    				  
		    				  // Animate the downVote!:
		    				  animateVote(elm, type, points);
		    			}
		    			else if (response.error == 'login')
		    			{
		    				showLogin('You must be logged in to vote! <span style="font-size:small">no account yet?: <a href="/auth/signup">sign up!</a></span>');
		    			}
		    			else {
		    				alert("Server AJAX error : " + response.message);
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
	var points = $("#recent-link"+number+"-points").attr('title');
	points = parseInt(points);
	
	if(type == 1)
	{
		if (!hasClass(elm.firstChild, "voted")) {
		addClass(elm.firstChild, "voted", '');	
		points++;
		$("#recent-link"+number+"-points").html( points.toString() + " points" );
		$("#recent-link"+number+"-points").effect("highlight", {color:'#53ff7b'}, 1000);
		}
		var el2 = document.getElementById("recent-link" + number + "-down");
		if (hasClass(el2.firstChild, "voted")) {
			removeClass(el2.firstChild, "voted", '');
		}
		//alert('You just upvoted story# ' + number);
	}
	else
	{
		if (!hasClass(elm.firstChild, "voted")) {
			addClass(elm.firstChild, "voted", '');
			points--;
			$("#recent-link"+number+"-points").html( points.toString() + " points" );
			$("#recent-link"+number+"-points").effect("highlight", {color:'#ff3a3a'}, 1000);
			}
		var el2 = document.getElementById("recent-link" + number + "-up");
		if (hasClass(el2.firstChild, "voted")) {
			removeClass(el2.firstChild, "voted", '');
			}
		//alert('You just downvoted story# ' + number);
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
		$('#username').focus();
		return false;
 }
  
  $(document).ready(function() {
	 
	  $('#search').defaultValue({'value':' Search Linkblab'});
	  
	  $(".decoda-spoilerBody").click(function(event) {
		  event.preventDefault();
		  if( window.console ) console.log("Spoiler Clicked");
		});

		$("#sortOptionsDropdown").linkselect({
			change: function(li, value, text){
				var g = value.split("|");
				if (g[0] == 'index') {
					var url = "http://linkblab.local/index/" + g[1];
					window.location.href = url;
				}
				else {
					var url = "http://linkblab.local/b/" + g[0] + "/" + g[1];
					window.location.href = url;
					
				}
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
		
		var username = $( "#username" ),
		password = $( "#password" ),
		allFields = $( [] ).add( username ).add( password ),
		usernameMsg = $("#usernameError"),
		passwordMsg = $("#passwordError");
		
		$('#ajaxLoader').hide();
		
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
							$('#ajaxLoader').show();
							},
							success : function(data) {
							$('#resetemail').attr('enabled', 'true');
							$('#ajaxLoader').hide();
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