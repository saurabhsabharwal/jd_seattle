<?php
// Include Bootstrap framework
JHtml::_('bootstrap.framework');

// Setup options object
$opt['parent'] = isset($params['parent']) ? ($params['parent'] == true ? '#' . $selector : $params['parent']) : false;
$opt['toggle'] = isset($params['toggle']) ? (boolean) $params['toggle'] : !($opt['parent'] === false || isset($params['active']));
$onShow = isset($params['onShow']) ? (string) $params['onShow'] : null;
$onShown = isset($params['onShown']) ? (string) $params['onShown'] : null;
$onHide = isset($params['onHide']) ? (string) $params['onHide'] : null;
$onHidden = isset($params['onHidden']) ? (string) $params['onHidden'] : null;

$options = JHtml::getJSObject($opt);

$opt['active'] = isset($params['active']) ? (string) $params['active'] : '';

// Build the script.
$script = array();
$script[] = "jQuery(function($){";
$script[] = "\t$('#" . $selector . "').collapse(" . $options . ")";

if ($onShow)
{
$script[] = "\t.on('show', " . $onShow . ")";
}

if ($onShown)
{
$script[] = "\t.on('shown', " . $onShown . ")";
}

if ($onHide)
{
$script[] = "\t.on('hideme', " . $onHide . ")";
}

if ($onHidden)
{
$script[] = "\t.on('hidden', " . $onHidden . ")";
}

$parents = array_key_exists(__METHOD__, static::$loaded) ? array_filter(array_column(static::$loaded[__METHOD__], 'parent')) : array();

if ($opt['parent'] && empty($parents))
{
$script[] = "
$(document).on('click.collapse.data-api', '[data-toggle=collapse]', function (e) {
var \$this   = $(this), href
var parent  = \$this.attr('data-parent')
var \$parent = parent && $(parent)

if (\$parent) \$parent.find('[data-toggle=collapse][data-parent=' + parent + ']').not(\$this).addClass('collapsed')
})";
}

$script[] = "});";

// Attach accordion to document
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

// Set static array
static::$loaded[__METHOD__][$selector] = $opt;

return '<div id="' . $selector . '" class="sella-accordion">';
?>
