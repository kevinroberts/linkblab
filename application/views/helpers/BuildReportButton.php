<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BuildReportButton helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildReportButton extends Zend_View_Helper_Abstract {
	

	/**
	 *  
	 */
	public function buildReportButton($linkID) {
		// TODO Auto-generated Zend_View_Helper_BuildReportButton::buildReportButton() helper 
		return '<form method="get" action="#" class="toggle report-button"><input type="hidden" value="reported" name="executed"><span class="option active"><a onclick="return toggle(this)" href="#">report</a></span><span style="display:none;" class="option error">are you sure?  <a onclick="change_state(this, &quot;report&quot;, hide_thing)" class="yes" href="javascript:void(0)">yes</a> / <a onclick="return toggle(this)" class="no" href="javascript:void(0)">no</a></span></form>';
	}
	
	
}
