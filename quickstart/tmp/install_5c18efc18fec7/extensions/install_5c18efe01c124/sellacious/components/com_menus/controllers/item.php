<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The Menu Item Controller
 *
 * @since  1.6
 */
class MenusControllerItem extends JControllerForm
{
	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 * @since   1.6
	 * @throws  Exception
	 */
	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->registerTask('setMenuType', 'setType');
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   3.6
	 */
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();

		$menuType   = isset($data['menutype']) ? $data['menutype'] : '';
		$menuType   = $app->input->getCmd('menutype', $menuType);
		$menutypeID = $menuType ? (int) $this->getMenuTypeId($menuType) : 0;

		return $user->authorise('core.create', 'com_menus.menu.' . $menutypeID);
	}

	/**
	 * Method to check if you edit a record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   3.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$user = JFactory::getUser();

		$menutypeID = 0;

		if (isset($data[$key]))
		{
			/** @var  MenusModelItem  $model */
			$model = $this->getModel();
			$item = $model->getItem($data[$key]);

			if (!empty($item->menutype))
			{
				// Protected Joomla menutype, do not allow edit
				if ($item->menutype == 'main')
				{
					return false;
				}

				$menutypeID = (int) $this->getMenuTypeId($item->menutype);
			}
		}

		return $user->authorise('core.edit', 'com_menus.menu.' . (int) $menutypeID);
	}

	/**
	 * Loads the menutype ID by a given menutype string
	 *
	 * @param   string  $menutype  The given menutype
	 *
	 * @return  integer
	 *
	 * @since   3.6
	 */
	protected function getMenuTypeId($menutype)
	{
		$model = $this->getModel();
		$table = $model->getTable('MenuType', 'JTable');

		$table->load(array('menutype' => $menutype));

		return (int) $table->get('id');
	}

	/**
	 * Method to add a new menu item.
	 *
	 * @return  mixed  True if the record can be added, a JError object if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{
		$app     = JFactory::getApplication();
		$context = 'com_menus.edit.item';
		$result   = parent::add();

		if ($result)
		{
			$app->setUserState($context . '.type', null);
			$app->setUserState($context . '.link', null);
		}

		return $result;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean	 True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.6
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel('Item', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=items' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   1.6
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app     = JFactory::getApplication();
		$context = 'com_menus.edit.item';
		$result  = parent::cancel();

		if ($result)
		{
			// Clear the ancillary data from the session.
			$app->setUserState($context . '.type', null);
			$app->setUserState($context . '.link', null);

			// Redirect to the list screen.
			// $menuType = $app->getUserState('com_menus.items.menutype', 'sellacious-menu');
			$append   = $this->getRedirectToListAppend();
			// $append   = '&menutype=' . $menuType . $append;
			$url      = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $append, false);

			$this->setRedirect($url);
		}

		return $result;
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  bool  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = null)
	{
		$app    = JFactory::getApplication();
		$result = parent::edit();

		if ($result)
		{
			// Push the new ancillary data into the session.
			$app->setUserState('com_menus.edit.item.type', null);
			$app->setUserState('com_menus.edit.item.link', null);
		}

		return $result;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   12.2
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		// Until we support frontend menu as well
		$app      = JFactory::getApplication();
		$app->input->set('client_id', '2', 'int');

		if ($recordId)
		{
			/** @var  MenusModelItem  $model */
			$model    = $this->getModel();
			$item     = $model->getItem($recordId);
			$clientId = $item->client_id;
			$append   = '&client_id=' . $clientId . $append;
		}
		else
		{
			$clientId = $app->input->get('client_id', '2', 'int');
			// $menuType = $app->input->get('menutype', 'sellacious-menu');
			$append   = '&client_id=' . $clientId . (empty($menuType) ? '' : '&menutype=' . $menuType) . $append;
		}

		return $append;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app      = JFactory::getApplication();
		$data     = $this->input->post->get('jform', array(), 'array');
		$task     = $this->getTask();
		$context  = 'com_menus.edit.item';

		/** @var  MenusModelItem  $model */
		$model    = $this->getModel('Item', '', array());
		$table    = $model->getTable();

		// Set the menutype should we need it.
		if ($data['menutype'] !== '')
		{
			$app->input->set('menutype', $data['menutype']);
		}

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = $this->input->getInt($urlVar);

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if ($model->checkin($data['id']) === false)
			{
				// Check-in failed, go back to the item and display a notice.
				$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'warning');

				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data['id'] = 0;
			$data['associations'] = array();
			$task = 'apply';
		}

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), !1));

			return false;
		}

		// Validate the posted data.
		// This post is made up of two forms, one for the item and one for params.
		/** @var  JForm  $form */
		$form = $model->getForm($data);

		if (!$form)
		{
			$this->setMessage($model->getError(), 'error');

			return false;
		}

		if ($data['type'] == 'url')
		{
			$data['link'] = str_replace(array('"', '>', '<'), '', $data['link']);

			if (strstr($data['link'], ':'))
			{
				$segments = explode(':', $data['link']);
				$protocol = strtolower($segments[0]);
				$scheme = array('http', 'https', 'ftp', 'ftps', 'gopher', 'mailto', 'news', 'prospero', 'telnet', 'rlogin', 'tn3270', 'wais', 'url',
					'mid', 'cid', 'nntp', 'tel', 'urn', 'ldap', 'file', 'fax', 'modem', 'git', 'sms');

				if (!in_array($protocol, $scheme))
				{
					$app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'warning');
					$this->setRedirect(
						JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false)
					);

					return false;
				}
			}
		}

		$data = $model->validate($form, $data);

		// Check for the special 'request' entry.
		if ($data['type'] == 'component' && isset($data['request']) && is_array($data['request']) && !empty($data['request']))
		{
			$removeArgs = array();

			// Preprocess request fields to ensure that we remove not set or empty request params
			/** @var  JFormField[] $request */
			$request = $form->getGroup('request');

			if (!empty($request))
			{
				foreach ($request as $field)
				{
					$fieldName = $field->getAttribute('name');

					if (!isset($data['request'][$fieldName]) || $data['request'][$fieldName] == '')
					{
						$removeArgs[$fieldName] = '';
					}
				}
			}

			// Parse the submitted link arguments.
			$args = array();
			parse_str(parse_url($data['link'], PHP_URL_QUERY), $args);

			// Merge in the user supplied request arguments.
			$args = array_merge($args, $data['request']);

			// Remove the unused request params
			if (!empty($args) && !empty($removeArgs))
			{
				$args = array_diff_key($args, $removeArgs);
			}

			$data['link'] = 'index.php?' . urldecode(http_build_query($args, '', '&'));
			unset($data['request']);
		}

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				$err = $errors[$i];
				$app->enqueueMessage($err instanceof Exception ? $err->getMessage() : $err, 'warning');
			}

			// Save the data in the session.
			$app->setUserState('com_menus.edit.item.data', $data);

			// Redirect back to the edit screen.
			$editUrl = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			$this->setRedirect(JRoute::_($editUrl, false));

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_menus.edit.item.data', $data);

			// Redirect back to the edit screen.
			$editUrl = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'error');
			$this->setRedirect(JRoute::_($editUrl, false));

			return false;
		}

		// Save succeeded, check-in the row.
		if ($model->checkin($data['id']) === false)
		{
			// Check-in failed, go back to the row and display a notice.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'warning');
			$redirectUrl = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			$this->setRedirect(JRoute::_($redirectUrl, false));

			return false;
		}

		$this->setMessage(JText::_('COM_MENUS_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_menus.edit.item.data', null);
				$app->setUserState('com_menus.edit.item.type', null);
				$app->setUserState('com_menus.edit.item.link', null);

				// Redirect back to the edit screen.
				$editUrl = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
				$this->setRedirect(JRoute::_($editUrl, false));
				break;

			case 'save2new':
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_menus.edit.item.data', null);
				$app->setUserState('com_menus.edit.item.type', null);
				$app->setUserState('com_menus.edit.item.link', null);

				// Redirect back to the edit screen.
				$editUrl = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend();
				$this->setRedirect(JRoute::_($editUrl, false));
				break;

			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_menus.edit.item.data', null);
				$app->setUserState('com_menus.edit.item.type', null);
				$app->setUserState('com_menus.edit.item.link', null);

				// Redirect to the list screen.
				// $menutype = $app->getUserState('com_menus.items.menutype', 'sellacious-menu');
				$append   = $this->getRedirectToListAppend() . (empty($menutype) ? '' : '&menutype=' . $menutype);

				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $append, false));
				break;
		}

		return true;
	}

	/**
	 * Sets the type of the menu item currently being edited.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function setType()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();

		// Get the posted values from the request.
		$data = $this->input->post->get('jform', array(), 'array');

		// Get the type.
		$type = $data['type'];

		$type     = json_decode(base64_decode($type));
		$title    = isset($type->title) ? $type->title : null;
		$recordId = isset($type->id) ? $type->id : 0;

		$specialTypes = array('alias', 'separator', 'url', 'heading', 'container');

		if (!in_array($title, $specialTypes))
		{
			$title = 'component';
		}
		else
		{
			// Set correct component id to ensure proper 404 messages with system links
			$data['component_id'] = 0;
		}

		$app->setUserState('com_menus.edit.item.type', $title);

		if ($title == 'component')
		{
			if (isset($type->request))
			{
				// Clean component name
				$type->request->option = JFilterInput::getInstance()->clean($type->request->option, 'CMD');
				$component             = JComponentHelper::getComponent($type->request->option);
				$data['component_id']  = $component->id;

				$link = 'index.php?' . JUri::buildQuery((array) $type->request);

				$app->setUserState('com_menus.edit.item.link', $link);
			}
		}
		// If the type is alias you just need the item id from the menu item referenced.
		elseif ($title == 'alias')
		{
			$app->setUserState('com_menus.edit.item.link', 'index.php?Itemid=');
		}

		unset($data['request']);

		$data['type'] = $title;

		if (strtolower($this->getTask()) == 'settype')
		{
			$data['link'] = $app->getUserState('com_menus.edit.item.link');
		}

		// Save the data in the session.
		$app->setUserState('com_menus.edit.item.data', $data);

		$this->type = $type;
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
	}

	/**
	 * Gets the parent items of the menu location currently.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function getParentItem()
	{
		$app = JFactory::getApplication();

		$results  = array();
		$menutype = $this->input->get->get('menutype');

		if ($menutype)
		{
			$model = $this->getModel('Items', '', array());
			$model->getState();
			$model->setState('filter.menutype', $menutype);
			$model->setState('list.select', 'a.id, a.title, a.level');
			$model->setState('list.start', '0');
			$model->setState('list.limit', '0');

			/** @var  MenusModelItems  $model */
			$results = $model->getItems();

			// Pad the option text with spaces using depth level as a multiplier.
			for ($i = 0, $n = count($results); $i < $n; $i++)
			{
				$results[$i]->title = str_repeat(' - ', $results[$i]->level) . $results[$i]->title;
			}
		}

		// Output a JSON object
		echo json_encode($results);

		$app->close();
	}
}