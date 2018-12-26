<?php
/**
 * @version     1.6.0
 * @package     sellacious
 *
 * @copyright   Copyright (C) 2012-2018 Bhartiy Web Technologies. All rights reserved.
 * @license     SPL Sellacious Private License; see http://www.sellacious.com/spl.html
 * @author      Izhar Aazmi <info@bhartiy.com> - http://www.bhartiy.com
 */
// no direct access.
defined('_JEXEC') or die;

use Sellacious\Import\ImportHandler;

/**
 * Importer model.
 *
 * @since   1.5.2
 */
class ImporterModelImport extends SellaciousModel
{
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getTable($name = '', $prefix = 'ImporterTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Upload the CSV method to auto-populate the userState.
	 *
	 * @param   string  $handler  The requested import handler identifier
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   1.5.2
	 */
	public function upload($handler)
	{
		$app = JFactory::getApplication();

		/**
		 * The plugin which decides to handle the request is responsible to validate and
		 * upload the source file to tmp destination.
		 *
		 * The plugin must also set the session state values that needs to persist for subsequent calls.
		 */
		$dispatcher = JEventDispatcher::getInstance();
		$result     = $dispatcher->trigger('onImportUploadSource', array('com_importer.import', $handler));

		if (count($result) == 0 || $app->getUserState('com_importer.import.state', null) === null)
		{
			throw new Exception(JText::_('COM_IMPORTER_IMPORT_ERROR_NO_UPLOAD_HANDLER'));
		}
	}

	/**
	 * Set the import options for the active import session from request.
	 * Plugin(s) shall detect the active state from session and act if needed.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   1.5.2
	 */
	public function setOptions()
	{
		/**
		 * The plugin which decides to handle the request is responsible to validate and
		 * upload the source file to tmp destination.
		 *
		 * The plugin must also set the session state values that needs to persist for subsequent calls.
		 */
		$dispatcher = JEventDispatcher::getInstance();
		$result     = $dispatcher->trigger('onImportSetOptions', array('com_importer.import'));

		if (count($result) == 0 || in_array(false, $result, true))
		{
			throw new Exception(JText::_('COM_IMPORTER_IMPORT_ERROR_NO_IMPORT_HANDLER'));
		}
	}

	/**
	 * Get a list of currently supported import handlers.
	 *
	 * @return  ImportHandler[]
	 * @throws  Exception
	 *
	 * @since   1.5.2
	 */
	public function getHandlers()
	{
		/**
		 * The plugin should populate the $handlers array as [name => title] with their supported handlers.
		 * Make sure that the names are unique so that they do not interfere with other plugins.
		 *
		 * @var  ImportHandler[]  $handlers
		 */
		$handlers   = array();
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onCollectHandlers', array('com_importer.import', &$handlers));

		$app    = JFactory::getApplication();
		$active = $app->getUserState('com_importer.import.state.handler');

		if (array_key_exists($active, $handlers))
		{
			$handlers[$active]->setActive(true);
		}

		return $handlers;
	}
}