<?php
/**
 * Recent pages, topics in forums, users, comments
 *
 * @package Cotonti
 * @version 0.7.0
 * @author esclkm, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2009
 * @license BSD
 */
if ( !defined('SED_CODE') ) { die("Wrong URL."); }
function sed_build_recentforums($template, $mode='recent', $maxperpage='5', $d=0, $titlelength=0, $rightprescan=true)
{
    global $totalrecent, $L, $cfg, $db_forum_sections, $db_forum_topics, $skin, $usr, $sys;
    $recentitems = new XTemplate(sed_skinfile($template, true));
    if($rightprescan)
    {
        // творим чудеса - читаем список разделов и к каким из них юзер имеет доступ
        $sql = sed_sql_query("SELECT * FROM $db_forum_sections
ORDER by fs_masterid DESC, fs_order ASC");
        unset ($catsub);
        $catsub = array();
        $catsub[] = $cat;
        while ($fsn = sed_sql_fetcharray($sql))
        {
            if (sed_auth('forums', $fsn['fs_id'], 'R'))
            {
                $catsub[] = $fsn['fs_id'];
                $forum_cats[$fsn['fs_id']] = $fsn;
            }
        }
        $incat= "AND ft_sectionid IN ('".implode("','", $catsub)."')";
    }

    //and ft_lastposterid!=".$usr['id']."

    if($mode=='recent')
    {
        $sql = sed_sql_query("SELECT * FROM $db_forum_topics
        WHERE ft_movedto=0 AND ft_mode=0 ".$incat."
        ORDER by ft_updated DESC LIMIT $maxperpage");
        $totalrecent['topics'] = $maxperpage;
    }
    else
    {
        $where ="WHERE ft_updated >= $mode ".$incat;
        $sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics ".$where);
        $totalrecent['topics'] = sed_sql_result($sql, 0, "COUNT(*)");
        $sql = sed_sql_query("SELECT * FROM $db_forum_topics ".$where." ORDER by ft_updated desc LIMIT $d, ".$maxperpage);
    }
    $ft_num=0;
    while ($row = sed_sql_fetcharray($sql))
    {

        $row['ft_icon'] = 'posts';
        $row['ft_postisnew'] = FALSE;
        $row['ft_pages'] = '';
        $ft_num++;
        if((int)$titlelength>0)
        {
            if(sed_string_truncate($row['ft_title'], $titlelength, false))
            {
                $row['ft_title'].="...";
            }
        }
        $build_forum=sed_build_forums($row['ft_sectionid'], sed_cutstring($forum_cats[$row['ft_sectionid']]['fs_title'],24), sed_cutstring($forum_cats[$row['ft_sectionid']]['fs_category'],16));
        $build_forum_full=sed_build_forums($row['ft_sectionid'], sed_cutstring($forum_cats[$row['ft_sectionid']]['fs_title'],24), sed_cutstring($forum_cats[$row['ft_sectionid']]['fs_category'],16), true, array($forum_cats[$row['ft_sectionid']]['fs_masterid'],$forum_cats[$row['ft_sectionid']]['fs_mastername']));
        $build_forum_short="<a href=\"".sed_url('forums', 'm=topics&s='.$row['ft_sectionid']).'">'.htmlspecialchars(sed_cutstring(stripslashes($forum_cats[$row['ft_sectionid']]['fs_title']),16))."</a>";


        if ($row['ft_mode']==1)
        {
            $row['ft_title'] = "# ".$row['ft_title'];
        }

        if ($row['ft_movedto']>0)
        {
            $row['ft_url'] = "forums.php?m=posts&amp;q=".$row['ft_movedto'];
            $row['ft_icon'] = $R['frm_icon_posts_moved'];
            $row['ft_title']= $L['Moved'].": ".$row['ft_title'];
            $row['ft_lastpostername'] = "&nbsp;";
            $row['ft_postcount'] = "&nbsp;";
            $row['ft_replycount'] = "&nbsp;";
            $row['ft_viewcount'] = "&nbsp;";
            $row['ft_lastpostername'] = "&nbsp;";
            $row['ft_lastposturl'] = "<a href=\"forums.php?m=posts&amp;q=".$row['ft_movedto']."&amp;n=last#bottom\">{$R['icon_follow']}</a> ".$L['Moved'];
            $row['ft_timago'] = sed_build_timegap($row['ft_updated'],$sys['now_offset']);
        }
        else
        {
            $row['ft_url'] = "forums.php?m=posts&amp;q=".$row['ft_id'];
            $row['ft_lastposturl'] = ($usr['id']>0 && $row['ft_updated'] > $usr['lastvisit']) ? "<a href=\"forums.php?m=posts&amp;q=".$row['ft_id']."&amp;n=unread#unread\">{$R['icon_unread']}</a>" : "<a href=\"forums.php?m=posts&amp;q=".$row['ft_id']."&amp;n=last#bottom\">{$R['icon_follow']}</a>";
            $row['ft_lastposturl'] .= @date($cfg['formatmonthdayhourmin'], $row['ft_updated'] + $usr['timezone'] * 3600);
            $row['ft_timago'] = sed_build_timegap($row['ft_updated'],$sys['now_offset']);
            $row['ft_replycount'] = $row['ft_postcount'] - 1;

            if ($row['ft_updated']>$usr['lastvisit'] && $usr['id']>0)
            {
                $row['ft_icon'] .= '_new';
                $row['ft_postisnew'] = TRUE;
            }

            if ($row['ft_postcount']>=$cfg['hottopictrigger'] && !$row['ft_state'] && !$row['ft_sticky'])
            {
                $row['ft_icon'] = ($row['ft_postisnew']) ? 'posts_new_hot' : 'posts_hot';
            }
            else
            {
                if ($row['ft_sticky'])
                {
                    $row['ft_icon'] .= '_sticky';
                }

                if ($row['ft_state'])
                {
                    $row['ft_icon'] .= '_locked';
                }
            }

            $row['ft_icon'] = sed_rc('frm_icon_topic_t', array('icon' => $row['ft_icon'], 'title' => $L['Rec_'.$row['ft_icon']]));
            $row['ft_lastpostername'] = sed_build_user($row['ft_lastposterid'], sed_cc($row['ft_lastpostername']));
        }

        $row['ft_firstpostername'] = sed_build_user($row['ft_firstposterid'], sed_cc($row['ft_firstpostername']));


        if ($row['ft_postcount']>$cfg['maxtopicsperpage'])
        {
            $row['ft_maxpages'] = ceil($row['ft_postcount'] / $cfg['maxtopicsperpage']);
            $row['ft_pages'] = $L['Pages'].":";
        }

        $recentitems -> assign(array(
    "FORUM_ROW_ID" => $row['ft_id'],
    "FORUM_ROW_STATE" => $row['ft_state'],
    "FORUM_ROW_ICON" => $row['ft_icon'],
    "FORUM_ROW_TITLE" => htmlspecialchars($row['ft_title']),
    "FORUM_ROW_PATH" => $build_forum,
    "FORUM_ROW_PATH_FULL" => $build_forum_full,
    "FORUM_ROW_PATH_SHORT" => $build_forum_short,
    "FORUM_ROW_DESC" => htmlspecialchars($row['ft_desc']),
    "FORUM_ROW_PREVIEW" => $row['ft_preview'].'...',
    "FORUM_ROW_CREATIONDATE" => @date($cfg['formatmonthdayhourmin'], $row['ft_creationdate'] + $usr['timezone'] * 3600),
    "FORUM_ROW_UPDATED" => $row['ft_lastposturl'],
    "FORUM_ROW_TIMEAGO" => $row['ft_timago'],
    "FORUM_ROW_POSTCOUNT" => $row['ft_postcount'],
    "FORUM_ROW_REPLYCOUNT" => $row['ft_replycount'],
    "FORUM_ROW_VIEWCOUNT" => $row['ft_viewcount'],
    "FORUM_ROW_FIRSTPOSTER" => $row['ft_firstpostername'],
    "FORUM_ROW_LASTPOSTER" => $row['ft_lastpostername'],
    "FORUM_ROW_URL" => $row['ft_url'],
    "FORUM_ROW_PAGES" => $row['ft_pages'],
    "FORUM_ROW_MAXPAGES" => $row['ft_maxpages'],
    "FORUM_ROW_NUM" => $ft_num,
    "FORUM_ROW_ODDEVEN" => sed_build_oddeven($ft_num),
    "FORUM_ROW" => $row,
            ));

        $recentitems->parse("MAIN.TOPICS_ROW");
    }

    if($d == 0 && $ft_num == 0)
    {
        $recentitems->parse("MAIN.NO_TOPICS_FOUND");
    }

    $recentitems->parse("MAIN");
    if($d == 0 || $ft_num > 0)
    {
        $res = $recentitems -> text("MAIN");
    }
    else
    {
        $res='';
    }
    return $res;
}

if (empty($extrafields_pag))
{
    /*  === get extra fields === */
    $extrafields_pag = array();
    $fieldsres = sed_sql_query("SELECT field_name, field_type FROM $db_extra_fields WHERE field_location='pages'");
    while ($row = sed_sql_fetchassoc($fieldsres)) $extrafields_pag[] = $row;
    /* ===== */
}

function sed_build_recentpages($template, $mode='recent', $maxperpage='5', $d=0, $titlelength=0, $textlength=0, $rightprescan=true, $cat='')
{
    global $sed_cat, $db_pages, $db_users, $sys, $cfg, $L, $pag,
    $usr, $extrafields_pag;
    $recentitems = new XTemplate(sed_skinfile($template, true));

    if($rightprescan || $cat)
    {
        // творим чудеса - читаем список разделов и к каким из них юзер имеет доступ
        unset($sedsub);
        $catsub = array();
        if(!empty($cat))
        {
            $mtch = $sed_cat[$cat]['path'].".";
            $mtchlen = mb_strlen($mtch);
            $catsub[] = $cat;
        }
        foreach($sed_cat as $i => $x)
        {
            if(sed_auth('page', $i, 'R') && (mb_substr($x['path'], 0, $mtchlen) == $mtch || empty($cat)))
            {
                $catsub[] = $i;
            }
        }
        $incat= "AND page_cat IN ('".implode("','", $catsub)."')";
    }

    if ($mode=='recent')
    {
        $where = "WHERE page_state=0 AND page_cat <> 'system' ".$incat;
        $totalrecent['pages'] = $cfg['plugin']['recentitems']['maxpages'];
    }
    else
    {
        //and ft_lastposterid!=".$usr['id']."
        $where = "WHERE page_date >= $mode AND page_state=0 AND page_cat <> 'system' ".$incat;
        $sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages ".$where);
        $totalrecent['pages'] = $maxperpage;
    }

    $sql = sed_sql_query("SELECT p.*, u.user_name, user_avatar FROM $db_pages AS p
    LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid ".$where." ORDER by page_date desc LIMIT $d, ".$maxperpage);

    $jj==0;
    while($pag = sed_sql_fetcharray($sql))
    {
        $jj++;
        $catpath = sed_build_catpath($pag['page_cat'], "<a href=\"%1\$s\">%2\$s</a>");
        if((int)$titlelength>0)
        {
            if(sed_string_truncate($pag['page_title'], $titlelength, false))
            {
                $pag['page_title'].="...";
            }
        }
        $pag['page_pageurl'] = (empty($pag['page_alias'])) ? sed_url('page', 'id='.$pag['page_id']) : sed_url('page', 'al='.$pag['page_alias']);
        $pag['page_fulltitle'] = $catpath." ".$cfg['separator']." <a href=\"".$pag['page_pageurl']."\">".htmlspecialchars($pag['page_title'])."</a>";

        $item_code = 'p'.$pag['page_id'];
        list($pag['page_comments'], $pag['page_comments_display']) = sed_build_comments($item_code, $pag['page_pageurl'], FALSE);
        list($pag['page_ratings'], $pag['page_ratings_display']) = sed_build_ratings($item_code, $pag['page_pageurl'], $ratings);

        switch($pag['page_type'])
        {
            case 2:
                if ($cfg['allowphp_pages'] && $cfg['allowphp_override'])
                {
                    ob_start();
                    eval($pag['page_text']);
                    $recentitems -> assign("PAGE_ROW_TEXT", ob_get_clean());
                }
                else
                {
                    $recentitems -> assign("PAGE_ROW_TEXT", "The PHP mode is disabled for pages.<br />Please see the administration panel, then \"Configuration\", then \"Parsers\".");
                }
                break;

            case 1:
                $pag_more = ((int)$textlength>0) ? sed_string_truncate($pag['page_text'], $textlength) : sed_cut_more($pag['page_text']);
                $recentitems -> assign("PAGE_ROW_TEXT", $pag['page_text']);
                break;

            default:
                if($cfg['parser_cache'])
                {
                    if(empty($pag['page_html']))
                    {
                        $pag['page_html'] = sed_parse(htmlspecialchars($pag['page_text']), $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1);
                        sed_sql_query("UPDATE $db_pages SET page_html = '".sed_sql_prep($pag['page_html'])."' WHERE page_id = " . $pag['page_id']);
                    }
                    $pag['page_html'] = ($cfg['parsebbcodepages']) ?  $pag['page_html'] : htmlspecialchars($pag['page_text']);
                    $pag_more = ((int)$textlength>0) ? sed_string_truncate($pag['page_html'], $textlength) : sed_cut_more($pag['page_html']);
                    $pag['page_html'] = sed_post_parse($pag['page_html'], 'pages');
                    $recentitems -> assign('PAGE_ROW_TEXT', $pag['page_html']);
                }
                else
                {
                    $pag['page_html'] = sed_parse(htmlspecialchars($pag['page_text']), $cfg['parsebbcodepages'], $cfg['parsesmiliespages'], 1);
                    $pag_more = ((int)$textlength>0) ? sed_string_truncate($pag['page_html'], $textlength) : sed_cut_more($pag['page_html']);
                    $pag['page_html'] = sed_post_parse($pag['page_html'], 'pages');
                    $recentitems -> assign('PAGE_ROW_TEXT', $pag['page_html']);
                }
                break;
        }


        $recentitems -> assign(array(
            "PAGE_ROW_URL" => $pag['page_pageurl'],
            "PAGE_ROW_ID" => $pag['page_id'],
            "PAGE_ROW_TITLE" => $pag['page_fulltitle'],
            "PAGE_ROW_SHORTTITLE" => htmlspecialchars($pag['page_title']),
            "PAGE_ROW_CAT" => $pag['page_cat'],
            "PAGE_ROW_CATTITLE" => htmlspecialchars($sed_cat[$pag['page_cat']]['title']),
            "PAGE_ROW_CATPATH" => $catpath,
            "PAGE_ROW_CATPATH_SHORT" => "<a href=\"".sed_url('list', 'c='.$pag['page_cat'])."\">".htmlspecialchars($sed_cat[$pag['page_cat']]['title'])."</a>",
            "PAGE_ROW_CATDESC" => htmlspecialchars($sed_cat[$pag['page_cat']]['desc']),
            "PAGE_ROW_CATICON" => $sed_cat[$pag['page_cat']]['icon'],
            "PAGE_ROW_KEY" => htmlspecialchars($pag['page_key']),
            "PAGE_ROW_DESC" => htmlspecialchars($pag['page_desc']),
            "PAGE_ROW_MORE" => ($pag_more) ? "<span class='readmore'><a href='".$pag['page_pageurl']."'>{$L['ReadMore']}</a></span>" : "",
            "PAGE_ROW_AUTHOR" => htmlspecialchars($pag['page_author']),
            "PAGE_ROW_OWNER" => sed_build_user($pag['page_ownerid'], htmlspecialchars($pag['user_name'])),
            "PAGE_ROW_AVATAR" => sed_build_userimage($pag['user_avatar'], 'avatar'),
            "PAGE_ROW_DATE" => @date($cfg['formatyearmonthday'], $pag['page_date'] + $usr['timezone'] * 3600),
            "PAGE_ROW_FILEURL" => $pag['page_url'],
            "PAGE_ROW_SIZE" => $pag['page_size'],
            "PAGE_ROW_COUNT" => $pag['page_count'],
            "PAGE_ROW_FILECOUNT" => $pag['page_filecount'],
            "PAGE_ROW_COMMENTS" => $pag['page_comments'],
            "PAGE_ROW_RATINGS" => $pag['page_ratings'],
            "PAGE_ROW_ODDEVEN" => sed_build_oddeven($jj),
            "PAGE_ROW_NUM" => $jj,
            ));
        // data from extra fields
        foreach ($extrafields_pag as $row)
        {
            $recentitems->assign('PAGE_ROW_' . mb_strtoupper($row['field_name']),
                sed_build_extrafields_data('page', $row['field_type'], $row['field_name'], $pag["page_{$row['field_name']}"])
            );
        }
        $recentitems->parse("MAIN.PAGE_ROW");
    }

    if($d == 0 && $jj == 0)
    {
        $recentitems->parse("MAIN.NO_PAGES_FOUND");
    }

    $recentitems->parse("MAIN");
    if($d == 0 || $jj > 0)
    {
        $res = $recentitems -> text("MAIN");
    }
    else
    {
        $res='';
    }
    return $res;
}

?>