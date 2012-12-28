var oTable;
$(document).ready(function() {
	$(".tab_content, #loader, #loader2").hide(); //Hide all content
	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});
	


		$( "#sortable" ).disableSelection();

		$('#form').submit( function() {
			var sData = $('input', oTable.fnGetNodes()).serialize();
			var answer = confirm("Are you sure you want to mark/unmark selected blabs as read only?");
			if (answer){
				return true;
			}
			else{
				return false;
			}

		} );

		oTable = $('#blabsGrid').dataTable({
			"bJQueryUI": true
		});

});

	function addToOpen(ele) {
		var valuep = ele.value;
		valuep = valuep.split('~');
		valuep = valuep[1];
		var current = $( "#unchecked" ).attr("value");
		if (current != '')
		{
		$( "#unchecked" ).attr("value", current + "," + valuep);
		}
		else
		{
	    $( "#unchecked" ).attr("value", valuep);
		}

	}