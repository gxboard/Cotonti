<?php
/**
 * English Language File for Search Plugin
 *
 * @package search
 * @version 0.7.0
 * @author Cotonti Translators Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Title & Subtitle
 */

$L['plu_search'] = 'Search';

/**
 * Plugin Body
 */

// Common - title, info, query
$L['plu_search_req'] = 'Query';
$L['plu_search_key'] = 'Find';
$L['plu_search_forums'] = 'Search forums';
$L['plu_search_pages'] = 'Search pages';

// Mode selectors and result titles
$L['plu_tabs_all'] = 'All';
$L['plu_tabs_frm'] = 'Forums';
$L['plu_tabs_pag'] = 'Pages';

// Parameters - common
$L['plu_ctrl_list'] = 'Hold CTRL to select multiple sections';
$L['plu_allsections'] = 'All sections';
$L['plu_allcategories'] = 'All categories';
$L['plu_res_sort'] = 'Order results by';
$L['plu_sort_desc'] = 'Descending';
$L['plu_sort_asc'] = 'Ascending';
$L['plu_other_opt'] = 'Optional parameters';
$L['plu_other_date'] = 'Including date';

// Parameters - dates
$L['plu_any_date'] = 'Any date';
$L['plu_last_2_weeks'] = 'Last 2 weeks';
$L['plu_last_1_month'] = 'Last month';
$L['plu_last_3_month'] = 'Last 3 months';
$L['plu_last_1_year'] = 'Last year';
$L['plu_need_datas'] = 'Custom range';

// Parameters - forums
$L['plu_frm_set_sec'] = 'Select forum sections';
$L['plu_frm_res_sort1'] = 'Topic updated';
$L['plu_frm_res_sort2'] = 'Topic started';
$L['plu_frm_res_sort3'] = 'Topic title';
$L['plu_frm_res_sort4'] = 'Replies count';
$L['plu_frm_res_sort5'] = 'Views count';
$L['plu_frm_res_sort6'] = 'Section';
$L['plu_frm_search_names'] = 'Search in topic titles';
$L['plu_frm_search_post'] = 'Search in posts';
$L['plu_frm_search_answ'] = 'Show topics with replies only';
$L['plu_other_userfilter'] = 'Filter users';

// Parameters - pages
$L['plu_pag_set_sec'] = 'Select page categories';
$L['plu_pag_res_sort1'] = 'Date published';
$L['plu_pag_res_sort2'] = 'Title';
$L['plu_pag_res_sort3'] = 'Popularity';
$L['plu_pag_res_sort3'] = 'Category';
$L['plu_pag_search_names'] = 'Search in page titles';
$L['plu_pag_search_desc'] = 'Search in page descriptions';
$L['plu_pag_search_text'] = 'Search in page text';
$L['plu_pag_search_file'] = 'Show pages with files only';

// Error messages
$L['plu_querytooshort'] = 'The query string is too short';
$L['plu_toomanywords'] = 'Too many words, limit is set to';
$L['plu_noneresult'] = 'Nothing was found. Please try to simplify your query';
$L['plu_usernotexist'] = 'User does not exists';

// Results
$L['plu_result'] = 'Search results';
$L['plu_found'] = 'Found';
$L['plu_match'] = 'matches';
$L['plu_section'] = 'Section';
$L['plu_last_date'] = 'Date updated';

/**
 * Plugin Config
 */

$L['cfg_maxwords']= array('Max. words in search query');
$L['cfg_maxsigns']= array('Max. latters in search query');
$L['cfg_maxitems']= array('Max. entries in normal search results');
$L['cfg_minsigns'] = array('Min. signs in query');
$L['cfg_pagesearch'] = array('Enable pages search');
$L['cfg_forumsearch'] = array('Enable forums search');
$L['cfg_searchurl'] = array('Type of forum post link to use', 'Single uses a Single post view, while Normal uses the traditional thread/jump-to link');
$L['cfg_addfields']= array('Additional pages fields for search, separated by commas', 'Example "page_extra1,page_extra2,page_key"');
$L['cfg_extrafilters']= array('Show extrafilters on main search page');

$L['info_desc'] = 'Search in pages, forums and other site areas with advanced options';

?>