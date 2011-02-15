window.onload = function(){
	var pw = parent.window;
	if(pw){
		var parentForm = $('.newCommentArea', pw.document);
		//var parentForm = pw.document.forms['newCommentForm'];
		//var childForm = document.forms['richEditForm']
		var childForm = $('#comment');
		
		childForm.val( parentForm.val());
		
		//childForm.elements['comment'].value = parentForm.elements['text'].value;
	
	}
}
function submitMe() {
	var pw = parent.window;
	if(pw){
		var parentForm = $('.newCommentArea', pw.document);
		var childForm = $('#comment');
		
		parentForm.val( childForm.val());
		//parentForm.elements['text'].value = childForm.elements['comment'].value;
		
		window.parent.Shadowbox.close();
	}
	return false;
}
function closeWindow() {
	$('#comment').val('');
	var pw = parent.window;
	if(pw){
	window.parent.Shadowbox.close();
	}
}

$(document).ready(function(){
	$('#comment').markItUp(myBbcodeSettings);
	window.setTimeout(function() {
		$('#comment').focus();

	}, 400);
  	
  });