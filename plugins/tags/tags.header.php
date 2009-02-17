<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=tags
Part=header
File=tags.header
Hooks=header.main
Tags=header.tpl:{HEADER_COMPOPUP}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

/**
 * Tags: supplimentary files connection
 *
 * @package Cotonti
 * @version 0.0.3
 * @author Trustmaster
 * @copyright (c) 2008-2009 Cotonti Team
 * @license BSD
 */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

if($cfg['plugin']['tags']['pages']
	&& (defined('SED_INDEX') || defined('SED_LIST') || defined('SED_PAGE'))
	|| $cfg['plugin']['tags']['forums'] && defined('SED_FORUMS')
	|| defined('SED_PLUG'))
{
	$out['compopup'] .= '<link rel="stylesheet" type="text/css" href="'.$cfg['plugins_dir'].'/tags/style.css" />';
}
?>
