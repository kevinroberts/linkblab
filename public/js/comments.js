Shadowbox.init({
	 skipSetup: true
});

function openRichEditor(ele) {

    Shadowbox.open({
        handleOversize: "drag",
        animate: false,
        animateFade: false,
        modal: true,
        content:    '/index/rich-editor',
        player:  "iframe",
        title:      "LinkBlab Rich Editor",
        height:     600,
        width:      800
    });
    
    return false;

}
$(document).ready(function(){	

	$('.mailLink').tooltip(); 

});