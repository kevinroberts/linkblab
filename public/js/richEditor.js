window.onload = function(){
	var pw = parent.window;
	if(pw){
		var parentForm = pw.document.forms['newCommentForm'];
		var childForm = document.forms['richEditForm'];
		
		childForm.elements['comment'].value = parentForm.elements['text'].value;
	
	}
}
function submitMe() {
	var pw = parent.window;
	if(pw){
		var parentForm = pw.document.forms['newCommentForm'];
		var childForm = document.forms['richEditForm'];
		
		parentForm.elements['text'].value = childForm.elements['comment'].value;
		
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
		$('#comment').trigger('focus');
	}, 400);
  	
  });