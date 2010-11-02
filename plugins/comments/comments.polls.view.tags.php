<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=polls.view.tags
Tags=polls.tpl:{POLLS_COMMENTS},{POLLS_COMMENTS_DISPLAY}
[END_COT_EXT]
==================== */

/**
 * Comments system for Cotonti
 *
 * @package comments
 * @version 0.7.0
 * @author Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

cot_require('comments', true);

$t->assign(array(
	'POLLS_COMMENTS' => cot_comments_link('polls', 'id='.$id, 'polls', $id),
	'POLLS_COMMENTS_DISPLAY' => cot_comments_display('polls', $id)
));

?>