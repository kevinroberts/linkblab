<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * formattingDialog helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_FormattingDialog extends Zend_View_Helper_Abstract {
	

	/**
	 *  
	 */
	public function formattingDialog() {

		$js = <<<EOT
		<script type="text/javascript">
$(function() {
		
		$( "#formatGuide" ).dialog({
			autoOpen: false,
			height: 700,
			width: 850,
			modal: true
			});
		});
	$('#open-format-dialog')
	.click(function() {
		$('#formatGuide').dialog('open');
	});
		</script>
EOT;
		$content = <<<EOT
<div id="formatGuide" title="Text formatting guide">
<ul style="padding-left: 10px">
<li>To make a piece of text bold enclose it in <strong>[b][/b]</strong>, e.g. <br><br><strong>[b]</strong>Hello<strong>[/b]</strong><br><br>will become <strong>Hello</strong><br /><br /></li>
<li>For underlining use <strong>[u][/u]</strong>, for example:<br><br><strong>[u]</strong>Good Morning<strong>[/u]</strong><br><br>becomes <span style="text-decoration: underline;">Good Morning</span><br /><br /></li>
<li>To italicise text use <strong>[i][/i]</strong>, e.g.<br><br>This is <strong>[i]</strong>Great!<strong>[/i]</strong><br><br>would give This is <i>Great!</i></li>
<li>Changing the colour of text is achieved by wrapping it in <strong>[color=][/color]</strong>. You can specify either a recognised colour name (eg. red, blue, yellow, etc.) or the hexadecimal triplet alternative, e.g. #FFFFFF, #000000. For example, to create red text you could use:<br><br><strong>[color=red]</strong>Hello!<strong>[/color]</strong><br><br>or<br><br><strong>[color=#FF0000]</strong>Hello!<strong>[/color]</strong><br><br>Both will output <span style="color: red;">Hello!</span><br /></li>
<li>Quoting text in replies: <strong>[quote=""][/quote]</strong> For example to quote a piece of text Mr. Blobby wrote you would enter:<br><br><strong>[quote="Mr. Blobby"]</strong></li>
<li> <strong>[url=][/url]</strong> tag, whatever you type after the = sign will cause the contents of that tag to act as a URL. For example to link to phpBB.com you could use:<br><br><strong>[url=http://www.phpbb.com/]</strong>Visit phpBB!<strong>[/url]</strong><br><br>This would generate the following link, <a href="http://www.phpbb.com/">Visit phpBB!</a> Please notice that the link opens in the same window or a new window depending on the users browser preferences.</li>
</ul>
</div>	
EOT;
		
		return $js.PHP_EOL.$content;
	}
	

}
