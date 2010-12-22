<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.edit.update.first
[END_COT_EXT]
==================== */

/**
 * Avatar and photo for users
 *
 * @package userimages
 * @version 0.9.1
 * @author Koradhil, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('userimages', 'plug');
$userimages = cot_userimages_config_get();

foreach($userimages as $code => $settings)
{
	$t->assign("USERS_EDIT_".strtoupper($code), cot_inputbox('text', "ruser$code", $urr["user_$code"], array('size' => 32, 'maxlength' => 255)));
}

?>