<?php
/**
 * @version     1.6.0
+ * @package     sellacious
+ *
+ * @copyright   Copyright (C) 2012-2018 Bhartiy Web Technologies. All rights reserved.
+ * @license     SPL Sellacious Private License; see http://www.sellacious.com/spl.html
+ * @author      Izhar Aazmi <info@bhartiy.com> - http://www.bhartiy.com
+ */
namespace Sellacious\Html;

// no direct access.
defined('_JEXEC') or die;

use JFactory;
use JHtml;
use JText;
use Joomla\Utilities\ArrayHelper;

/**
 * @package  Fix for Joomla Select lists to use JLayouts
 *
 * @since    __DEPLOY_VERSION__
 */

class Accordion
{
	/**
	 * Add javascript support for Bootstrap accordians and insert the accordian
	 *
	 * @param   string  $selector  The ID selector for the tooltip.
	 * @param   array   $params    An array of options for the tooltip.
	 *                             Options for the tooltip can be:
	 *                             - parent  selector  If selector then all collapsible elements under the specified parent will be closed when this
	 *                                                 collapsible item is shown. (similar to traditional accordion behavior)
	 *                             - toggle  boolean   Toggles the collapsible element on invocation
	 *                             - active  string    Sets the active slide during load
	 *
	 *                             - onShow    function  This event fires immediately when the show instance method is called.
	 *                             - onShown   function  This event is fired when a collapse element has been made visible to the user
	 *                                                   (will wait for css transitions to complete).
	 *                             - onHide    function  This event is fired immediately when the hide method has been called.
	 *                             - onHidden  function  This event is fired when a collapse element has been hidden from the user
	 *                                                   (will wait for css transitions to complete).
	 *
	 * @return  string  HTML for the accordian
	 *
	 * @since   3.0
	 */

	public static function startAccordion($selector = 'myAccordian', $params = array())
	{
		if (!isset(static::$loaded[__METHOD__][$selector]))
		{
		$html = \JLayoutHelper::render('sellacious.html.accordion.accordion', get_defined_vars());
	}}
}
