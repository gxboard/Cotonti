<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=pm.reply.tags,pm.send.tags
Tags=pm.message.tpl:{PM_FORM_PFS};pm.send.tpl:{PMSEND_FORM_PFS}
[END_COT_EXT]
==================== */

/**
 * PFS link for forums.editpost
 *
 * @package pfs
 * @version 0.7.0
 * @author Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2012
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('pfs', 'module');

$pfs = cot_build_pfs($usr['id'], 'newlink', 'newpmtext', $L['Mypfs']);
$pfs .= (cot_auth('pfs', 'a', 'A')) ? ' &nbsp; '.cot_build_pfs(0, 'newlink', 'newpmtext', $L['SFS']) : '';

$pfs_tag = cot_get_caller() == 'pm.send' ? 'PMSEND_FORM_PFS' : 'PM_FORM_PFS';

$t->assign($pfs_tag, $pfs);
?>
