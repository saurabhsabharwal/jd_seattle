<?php
/**
 * @version     1.0.0
 * @package     sellacious.plugin
 *
 * @copyright   Copyright (C) 2016. Bhartiy Web Technologies. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Izhar Aazmi <info@bhartiy.com> - http://www.bhartiy.com
 */
defined('_JEXEC') or die;

/**
 * Sellacious MooToolsKiller plugin.
 *
 * @since  1.0.0
 */
class PlgSystemMooKiller extends JPlugin
{
	/**
	 * Prevent loading of MooTools library from Joomla core into sellacious backoffice.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterInitialise()
	{
		if (JFactory::getApplication()->getName() == 'sellacious')
		{
			JHtml::register('jhtml.behavior.framework', array($this, 'bypass'));
			JHtml::register('jhtml.behavior.modal', array($this, 'bypass'));
			JHtml::register('jhtml.behavior.tooltip', array($this, 'bypass'));
		}
	}

	/**
	 * Just does nothing
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function bypass()
	{
	}
}
