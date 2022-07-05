<?php
wdja_cms_admin_init();

function pp_manage_navigation() {
    return ii_ireplace('manage.navigation', 'tpl');
}

function pp_manage_backUpAll($time='') {
    global $conn, $variable, $db_database, $nbackuppath;
    ii_conn_init();
    $bool = false;
    $tsqlstr = "SHOW TABLES FROM $db_database";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_assoc($trs)) {
        foreach ($row as $k => $v) {
            $data .= get_create_table($v);
        }
    }
    $data = rtrim($data, "\r\n\r\n");//删除最后一个换行
    if (!ii_isnull($time)) $filename = $nbackuppath .'wdja'. '_' . $time . '.sql';
    else $filename = $nbackuppath . $time .'wdja.sql';
    if (file_put_contents($filename, $data)) $bool = true;
    return $bool;
}

function pp_manage_dellAll() {
    global $conn, $variable, $db_database, $nbackuppath;
    ii_conn_init();
    $bool = false;
    $time = ii_format_date(ii_now(), 0);
    pp_manage_backUpAll($time);//删除前先备份数据库
    $tsqlstr = "SHOW TABLES FROM $db_database";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_assoc($trs)) {
        foreach ($row as $k => $v) {
            $tsqlstr2 = "drop table $v";
            $trs2 = ii_conn_query($tsqlstr2, $conn);
            ii_conn_free_result($trs2);
            unset($trs2);
        }
    }
}

function wdja_cms_admin_manage_deldisp() {
    global $ngenre, $slng;
    global $conn;
    global $ndatabase, $nidfield, $nfpre, $nsaveimages;
    $tbackurl = $_GET['backurl'];
    $ttype = ii_cstr($_GET['type']);
    $ttable = ii_cstr($_GET['table']);
    if ($ttype == 'table') {
        $tsqlstr = "DROP TABLE $ttable";
        wdja_cms_admin_manage_backupdisp($ttable);
        $trs = ii_conn_query($tsqlstr, $conn);
        if ($trs) {
            wdja_cms_admin_msg(ii_itake('manage.del_succeed', 'lng') , $tbackurl, 1);
        } else wdja_cms_admin_msg(ii_itake('manage.del_failed', 'lng') , $tbackurl, 1);
    } else {
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng') , $tbackurl, 1);
    }
}

function wdja_cms_admin_manage_deldisp_field() {
    global $ngenre, $slng;
    global $conn;
    global $ndatabase, $nidfield, $nfpre, $nsaveimages;
    $tbackurl = $_GET['backurl'];
    $ttable = ii_cstr($_GET['table']);
    $tfield = ii_cstr($_GET['field']);
    if (!ii_isnull($ttable) && !ii_isnull($tfield)) {
        $tsqlstr = "ALTER TABLE `".$ttable."` DROP `".$tfield."`";
        wdja_cms_admin_manage_backupdisp($ttable);
        $trs = ii_conn_query($tsqlstr, $conn);
        if ($trs) {
            wdja_cms_admin_msg(ii_itake('manage.del_field_succeed', 'lng') , $tbackurl, 1);
        } else wdja_cms_admin_msg(ii_itake('manage.del_field_failed', 'lng') , $tbackurl, 1);
    } else {
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng') , $tbackurl, 1);
    }
}

function wdja_cms_admin_manage_adddisp() {
    global $ngenre, $slng;
    global $conn;
    global $ndatabase, $nidfield, $nfpre, $nsaveimages;
    $tbackurl = $_GET['backurl'];
    $tctable_type = ii_get_num($_POST['ctable_type'],0);
    $tcopy_type = ii_get_num($_POST['copy_type'],0);
    $tctable = ii_cstr($_POST['ctable']);
    $totable = ii_cstr($_POST['otable']);
    $tid = ii_cstr($_POST['id']);
    $tfpre = ii_cstr($_POST['fpre']);
    if ($tctable_type == 0) {
        $tsqlstr = "CREATE TABLE " . $tctable . " 
        (
          " . $tid . " int NOT NULL AUTO_INCREMENT,
          " . $tfpre . "titles varchar(250) DEFAULT NULL,
          " . $tfpre . "topic varchar(252) DEFAULT NULL,
          " . $tfpre . "keywords varchar(252) DEFAULT NULL,
          " . $tfpre . "description varchar(252) DEFAULT NULL,
          " . $tfpre . "time datetime DEFAULT NULL,
          " . $tfpre . "hidden int DEFAULT '0',
          " . $tfpre . "update datetime DEFAULT NULL,
          " . $tfpre . "good int DEFAULT '0',
          " . $tfpre . "count int DEFAULT '0',
          " . $tfpre . "lng varchar(50) DEFAULT NULL,
          PRIMARY KEY (".$tid.")
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        $trs = ii_conn_query($tsqlstr, $conn);
        if ($trs)wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng') , $tbackurl, 1);
        else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng') , $tbackurl, 1);
    } elseif ($tctable_type == 1) {
        if ($tcopy_type == 0) {
            $tsqlstr = "CREATE TABLE " . $tctable . " LIKE " . $totable;
            $trs = ii_conn_query($tsqlstr, $conn);
            if ($trs)wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng') , $tbackurl, 1);
            else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng') , $tbackurl, 1);
        }elseif ($tcopy_type ==1) {
            $tsqlstr = "CREATE TABLE " . $tctable . " LIKE " . $totable;
            $trs = ii_conn_query($tsqlstr, $conn);
            $tsqlstr2 = "INSERT INTO " . $tctable . " SELECT * FROM " . $totable;
            $trs2 = ii_conn_query($tsqlstr2, $conn);
            if ($trs && $trs2)wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng') , $tbackurl, 1);
            else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng') , $tbackurl, 1);

        }else{
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng') , $tbackurl, 1);
        }
    }else{
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng') , $tbackurl, 1);
    }
}

function wdja_cms_admin_manage_editdisp() {
    global $ngenre;
    global $conn;
    global $ndatabase, $nidfield, $nfpre, $nsaveimages;
    $tbackurl = $_GET['backurl'];
    $tclass_list = ii_get_safecode($_POST['sort_list']);
    $tclass = ii_get_lrstr($tclass_list, ',', 'left');
    $tid = ii_get_num($_GET['id']);
    $timage = ii_left(ii_cstr($_POST['image']) , 255);
    if (mm_search_field($ngenre, ii_cstr($_POST['ucode']) , 'ucode', $tid) && !ii_isnull($_POST['ucode'])) wdja_cms_admin_msg(ii_itake('manage.ucode_failed', 'lng') , $tbackurl, 1);
    if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])) , 100000);
    else $tcontent = ii_left(ii_cstr($_POST['content']) , 100000);
    $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']) , 10000);
    if (!($tclass == 0)) {
        $tsqlstr = "update $ndatabase set
    " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']) , 250) . "',
    " . ii_cfname('keywords') . "='" . ii_left(ii_cstr($_POST['keywords']) , 250) . "',
    " . ii_cfname('description') . "='" . ii_left(ii_cstr($_POST['description']) , 250) . "',
    " . ii_cfname('image') . "='$timage',
    " . ii_cfname('content') . "='$tcontent',
    " . ii_cfname('content_atts_list') . "='$tcontent_atts_list',
    " . ii_cfname('ucode') . "='" . ii_left(ii_cstr($_POST['ucode']) , 50) . "',
    " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
    " . ii_cfname('update') . "='" . ii_now() . "',
    " . ii_cfname('cls') . "='" . mm_get_sort_cls($tclass) . "',
    " . ii_cfname('class') . "=$tclass,
    " . ii_cfname('class_list') . "='" . $tclass_list . "',
    " . ii_cfname('count') . "=" . ii_get_num($_POST['count']) . ",
    " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . ",
    " . ii_cfname('good') . "=" . ii_get_num($_POST['good']) . "
    where $nidfield=$tid";
        $trs = ii_conn_query($tsqlstr, $conn);
        if ($trs) {
            $upfid = $tid;
            api_update_fields($upfid);
            api_update_tags($upfid);
            if (ii_get_num($_POST['hidden']) == 0) {
                if (mm_search_baidu(array(
                    'genre' => $ngenre,
                    'gid' => $upfid
                ))) mm_baidu_push('update', $ngenre, ii_left(ii_cstr($_POST['topic']) , 250) , $upfid);
                else mm_baidu_push('urls', $ngenre, ii_left(ii_cstr($_POST['topic']) , 250) , $upfid);
            } else {
                mm_baidu_push('del', $ngenre, ii_left(ii_cstr($_POST['topic']) , 250) , $upfid);
            }
            uu_upload_update_database_note($ngenre, $tcontent_atts_list, 'content_atts', $upfid);
            wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng') , $tbackurl, 1);
        } else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng') , $tbackurl, 1);
    } else {
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng') , $tbackurl, 1);
    }
}

function wdja_cms_admin_manage_adddisp_field() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tbackurl = $_GET['backurl'];
    $ttable = ii_get_safecode($_GET['table']);
    $tfield = ii_get_safecode($_POST['field']);
    $ttype = ii_get_safecode($_POST['type']);
    $tdefault = ii_get_safecode($_POST['default']);
    $tnull = ii_get_safecode($_POST['null']);
    $tcomment = ii_get_safecode($_POST['comment']);
    $tafter = ii_get_safecode($_POST['after']);
    switch ($tdefault) {
        case 'NULL':
            $tdefault = "DEFAULT NULL";
            break;
        case '0':
            $tdefault = "DEFAULT '0'";
            break;
        case 'CURRENT_TIMESTAMP':
            $tdefault = "DEFAULT CURRENT_TIMESTAMP";
            break;
        default:
            $tdefault = "";
            break; 
    }
    switch ($tnull) {
        case 'NULL':
            $tnull = "NULL";
            break;
        case 'NOT NULL':
            $tnull = "NOT NULL";
            break;
    }
    $sql = "ALTER TABLE `".$ttable."` ADD `".$tfield."`";
    if ($ttype == 'int') {
        $tlong = ii_get_num($_POST['long'],9);
        $sql .= " INT(".$tlong.") ";
    }elseif ($ttype == 'varchar') {
        $tlong = ii_get_num($_POST['long'],255);
        $sql .= " VARCHAR(".$tlong.") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ";
    }elseif ($ttype == 'text') {
        $sql .= " TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ";
    }elseif ($ttype == 'datetime') {
        $sql .= " DATETIME ";
    }
    $sql .= " COMMENT '".$tcomment."' ".$tnull." ".$tdefault." AFTER `".$tafter."`";
    $trs = ii_conn_query($sql, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('manage.add_field_succeed', 'lng') , $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('manage.add_field_failed', 'lng') , $tbackurl, 1);
}


function wdja_cms_admin_manage_editdisp_field() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tbackurl = $_GET['backurl'];
    $ttable = ii_get_safecode($_GET['table']);
    $tfield = $_GET['field'];
    $tnfield = $_POST['field'];
    $ttype = ii_get_safecode($_POST['type']);
    $tdefault = ii_get_safecode($_POST['default']);
    $tdiy = ii_get_safecode($_POST['diy']);
    $tnull = ii_get_safecode($_POST['null']);
    $textra = ii_get_safecode($_POST['extra']);
    $tcomment = ii_get_safecode($_POST['comment']);
    switch ($tdefault) {
        case 'NULL':
            $tdefault = "DEFAULT NULL";
            break;
        case 'CURRENT_TIMESTAMP':
            $tdefault = "DEFAULT CURRENT_TIMESTAMP";
            break;
        case 'DIY':
            $tdefault = "DEFAULT '".$tdiy."'";
            break;
        default:
            $tdefault = "";
            break; 
    }
    switch ($tnull) {
        case 'NULL':
            $tnull = "NULL";
            break;
        case 'NOT NULL':
            $tnull = "NOT NULL";
            break;
    }
    $sql = "ALTER TABLE `".$ttable."` CHANGE `".$tfield."` `".$tnfield."`";
    if ($ttype == 'int') {
        $tlong = ii_get_num($_POST['long'],9);
        $sql .= " INT(".$tlong.") ";
    }elseif ($ttype == 'varchar') {
        $tlong = ii_get_num($_POST['long'],255);
        $sql .= " VARCHAR(".$tlong.") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ";
    }elseif ($ttype == 'text') {
        $sql .= " TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ";
    }elseif ($ttype == 'datetime') {
        $sql .= " DATETIME ";
    }
    if(!ii_isnull($tcomment)) $sql .= " COMMENT '".$tcomment."' ".$tnull." ".$tdefault;
    else  $sql .= " ".$tnull." ".$tdefault;
    if(!ii_isnull($textra)) $sql .= " ".$textra;
    $trs = ii_conn_query($sql, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('manage.edit_field_succeed', 'lng') , $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('manage.edit_field_failed', 'lng') , $tbackurl, 1);
}

function wdja_cms_admin_manage_backupdisp($table = '') {
    global $conn, $variable, $db_database, $nbackuppath;
    ii_conn_init();
    $tbackurl = $_GET['backurl'];
    $ttable = $_GET['table'];
    if (!ii_isnull($table)) $ttable = $table;
    $data = genTitle();//备份单独表时,取消外键约束
    if (!ii_isnull($ttable)) {
        $data .= get_create_table($ttable);
        $filename = $nbackuppath . $ttable . '_' . time() . '.sql';
        if (file_put_contents($filename, $data)) {
            if (ii_isnull($table)) wdja_cms_admin_msg(ii_itake('manage.backup_succeed', 'lng') , $tbackurl, 1);
        } else {
            if (ii_isnull($table)) wdja_cms_admin_msg(ii_itake('manage.backup_failed', 'lng') , $tbackurl, 1);
        }
    } else {
        pp_manage_backUpAll();
    }
}

function wdja_cms_admin_manage_restoredisp()
    {
    ini_set('max_execution_time','300');//防止超时,如果数据太大,建议直接在数据库中还原.
    global $conn, $nbackuppath, $db_database;
    $tbackurl = $_GET['backurl'];
    $ttable = $_GET['table'];
    $tfile = $_GET['file'];
    if (ii_isnull($tfile)) $nfile = 'wdja.sql';
    else $nfile = $tfile;
    $filename = $nbackuppath.$nfile;
    if (!file_exists($filename)) return;
    $str = fread( $hd = fopen($filename, "rb") , filesize($filename));
    $sqls = explode("\r\n\r\n", $str);
    $i = 0;
    if (is_array($sqls))
    {
        if (ii_isnull($tfile)) pp_manage_dellAll();//还原数据库之前删除所有表
        foreach($sqls as $sql)
        {
            if (!ii_isnull($sql) &&strlen($sql)>3 && (substr(ltrim($sql),0,2)!='/*' || substr(ltrim($sql),0,3)!='-- ')) {
                $trs = ii_conn_query($sql, $conn);
                if (!$trs) $i++;
                ii_conn_free_result($trs);
                unset($trs);
            }
        }
    unset($sqls);
    }
    fclose($hd);
    $num = str_replace('[]', '['.$i.']', ii_itake('manage.error_num', 'lng'));
    wdja_cms_admin_msg(ii_itake('manage.restore_succeed', 'lng').','.$num , $tbackurl, 1);
}

function wdja_cms_admin_manage_action() {
    global $ndatabase, $nidfield, $nfpre, $ncontrol;
    switch ($_GET['action']) {
        case 'add':
            wdja_cms_admin_manage_adddisp();
            break;
        case 'add_field':
            wdja_cms_admin_manage_adddisp_field();
            break;
        case 'edit':
            wdja_cms_admin_manage_editdisp();
            break;
        case 'edit_field':
            wdja_cms_admin_manage_editdisp_field();
            break;
        case 'delete':
            wdja_cms_admin_manage_deldisp();
            break;
        case 'delete_field':
            wdja_cms_admin_manage_deldisp_field();
            break;
        case 'delete_file':
            wdja_cms_admin_manage_deldisp_file();
            break;
        case 'backup':
            wdja_cms_admin_manage_backupdisp();
            break;
        case 'restore':
            wdja_cms_admin_manage_restoredisp();
            break;
    }
}

function wdja_cms_admin_manage_deldisp_file() {
    global $nbackuppath;
    $tbackurl = $_GET['backurl'];
    $file = $nbackuppath . $_GET['file'];
    if (file_exists($file)) {
        if (unlink($file)) {
            wdja_cms_admin_msg(ii_itake('manage.delfile_succeed', 'lng') , $tbackurl, 1);
        } else {
            wdja_cms_admin_msg(ii_itake('manage.delfile_failed', 'lng') , $tbackurl, 1);
        }
    }
}

function wdja_cms_admin_manage_restore() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tmpstr = ii_itake('manage.restore', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tmprstr = '';
    $row = get_backup_fileInfo();
    if (is_array($row)) {
        foreach ($row as $key => $val) {
            $name = ii_get_lrstr($val['name'], '.', 'leftr');
            $table = ii_get_lrstr($name, '_', 'leftr');
            if (strpos($table, '_') !== false) {
                $tmptstr = str_replace('{$name}', $val['name'], $tmpastr);
                $tmptstr = str_replace('{$table}', $table, $tmptstr);
                $tmptstr = str_replace('{$size}', ii_csize($val['size']) , $tmptstr);
                $tmptstr = str_replace('{$time}', $val['time'], $tmptstr);
                $tmptstr = str_replace('{$path}', $val['path'], $tmptstr);
                $tmprstr .= $tmptstr;
            }
        }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage_add() {
    global $nupsimg, $nupsimgs;
    $tbackurl = $_GET['backurl'];
    $tmpstr = ii_itake('manage.add', 'tpl');
    $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage_edit() {
    global $conn;
    global $ndatabase, $nidfield, $nfpre, $nupsimg, $nupsimgs;
    $tid = ii_get_num($_GET['id']);
    $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) {
        $tmpstr = ii_itake('manage.edit', 'tpl');
        foreach ($trs as $key => $val) {
            $tkey = ii_get_lrstr($key, '_', 'rightr');
            $GLOBALS['RS_' . $tkey] = $val;
            $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val) , $tmpstr);
        }
        $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
        $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
        $tmpstr = str_replace('{$upsimgs}', $nupsimgs, $tmpstr);
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
    } else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng') , -1);
}

function wdja_cms_admin_manage_edit_field() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tbackurl = $_GET['backurl'];
    $ttable = $_GET['table'];
    $tfield = $_GET['field'];
    $tmpstr = ii_itake('manage.edit_field', 'tpl');
    $tsqlstr = "show full columns from $ttable";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_all($trs)) {
        if (is_array($row)) {
            foreach ($row as $key => $val) {
                if ($val['Field'] == $tfield) {
                    preg_match_all("|\((.*)\)|U", $val['Type'], $long);
                    $tmptstr = str_replace('{$Field}', $val['Field'], $tmpstr);
                    $tmptstr = str_replace('{$Type}', ii_get_lrstr($val['Type'], '(', 'left') , $tmptstr);
                    if (is_array($long)) $tmptstr = str_replace('{$Long}', $long[1][0], $tmptstr);
                    else $tmptstr = str_replace('{$Long}', '', $tmptstr);
                    if($val['Null'] == 'YES') $tmptstr = str_replace('{$Null}', 'NULL', $tmptstr);
                    else $tmptstr = str_replace('{$Null}', 'NOT NULL', $tmptstr);
                    $tmptstr = str_replace('{$Collation}', $val['Collation'], $tmptstr);
                    $tmptstr = str_replace('{$Key}', $val['Key'], $tmptstr);
                    if($val['Default'] != '' && $val['Default'] != 'NULL' && $val['Default'] != 'CURRENT_TIMESTAMP'){
                         $tmptstr = str_replace('{$Default}', 'DIY', $tmptstr);
                         $tmptstr = str_replace('{$diy}', $val['Default'], $tmptstr);
                    }else{
                        $tmptstr = str_replace('{$Default}', $val['Default'], $tmptstr);
                        $tmptstr = str_replace('{$diy}', '', $tmptstr);
                    }
                    $tmptstr = str_replace('{$Extra}', $val['Extra'], $tmptstr);
                    $tmptstr = str_replace('{$Comment}', $val['Comment'], $tmptstr);
                    $tmprstr .= $tmptstr;
                }
            }
        }
    }
    $tmpstr = str_replace('{$table}', $ttable, $tmprstr);
    $tmpstr = str_replace('{$field}', $tfield, $tmpstr);
    $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage_add_field() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tbackurl = $_GET['backurl'];
    $ttable = $_GET['table'];
    $tmpstr = ii_itake('manage.add_field', 'tpl');
    $tmpstr = str_replace('{$table}', $ttable, $tmpstr);
    $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage_list() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $toffset = ii_get_num($_GET['offset']);
    $tmpstr = ii_itake('manage.list', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tmprstr = '';
    $tsqlstr = "SHOW TABLES FROM $db_database";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_all($trs)) {
        if (is_array($row)) {
            foreach ($row as $key => $val) {
                foreach ($val as $k => $v) {
                    $tmptstr = str_replace('{$topic}', $v, $tmpastr);
                    $tmprstr .= $tmptstr;
                }
            }
        }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage_list_field() {
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $ttable = $_GET['table'];
    $tmpstr = ii_itake('manage.list_field', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
    $tmprstr = '';
    $tsqlstr = "show full columns from $ttable";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_all($trs)) {
        if (is_array($row)) {
            foreach ($row as $key => $val) {
                $tmptstr = str_replace('{$Field}', $val['Field'], $tmpastr);
                $tmptstr = str_replace('{$Type}', $val['Type'], $tmptstr);
                $tmptstr = str_replace('{$Null}', $val['Null'], $tmptstr);
                $tmptstr = str_replace('{$Key}', $val['Key'], $tmptstr);
                $tmptstr = str_replace('{$Default}', $val['Default'], $tmptstr);
                $tmptstr = str_replace('{$Extra}', $val['Extra'], $tmptstr);
                $tmptstr = str_replace('{$Collation}', $val['Collation'], $tmptstr);
                $tmptstr = str_replace('{$Comment}', $val['Comment'], $tmptstr);
                $tmprstr .= $tmptstr;
            }
        }
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$table}', $ttable, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
}

function wdja_cms_admin_manage() {
    switch ($_GET['type']) {
        case 'add':
            return wdja_cms_admin_manage_add();
            break;
        case 'add_field':
            return wdja_cms_admin_manage_add_field();
            break;
        case 'edit':
            return wdja_cms_admin_manage_edit();
            break;
        case 'edit_field':
            return wdja_cms_admin_manage_edit_field();
            break;
        case 'list':
            return wdja_cms_admin_manage_list();
            break;
        case 'list_field':
            return wdja_cms_admin_manage_list_field();
            break;
        case 'restore':
            return wdja_cms_admin_manage_restore();
            break;
        default:
            return wdja_cms_admin_manage_list();
            break;
    }
}

function mm_sel_table_list()
{
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $tsqlstr = "SHOW TABLES FROM $db_database";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_all($trs)) {
        if (is_array($row)) {
            $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
            $option_pre = '';
            $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
            $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
            $tmpstr = '';
            $treturnstr = '';
            foreach ($row as $val)
            {
              $tmpstr = $option_unselected;
              $tmpstr = str_replace('{$explain}', $val['Tables_in_'.$db_database], $tmpstr);
              $tmpstr = str_replace('{$value}', $val['Tables_in_'.$db_database], $tmpstr);
              $treturnstr .= $tmpstr;
            }
            return $option_pre.$treturnstr;
        }else{
            return $option_pre;
        }
    }
}

function mm_sel_field_list($table)
{
    global $conn, $variable, $db_database;
    global $ngenre, $slng;
    $ttable = ii_get_safecode($table);
    $tsqlstr = "desc $ttable";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($row = ii_conn_fetch_all($trs)) {
        if (is_array($row)) {
            $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
            $option_pre = '';
            $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
            $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
            $tmpstr = '';
            $treturnstr = '';
            foreach ($row as $val)
            {
              $tmpstr = $option_unselected;
              $tmpstr = str_replace('{$explain}', $val['Field'], $tmpstr);
              $tmpstr = str_replace('{$value}', $val['Field'], $tmpstr);
              $treturnstr .= $tmpstr;
            }
            return $option_pre.$treturnstr;
        }else{
            return $option_pre;
        }
    }
}

function get_backup_fileInfo() {
    global $nbackuppath;
    $temp = array();
    if (is_dir($nbackuppath)) {
        $handler = opendir($nbackuppath);
        $num = 0;
        while ($file = readdir($handler)) {
            if ($file !== '.' && $file !== '..') {
                $filename = $nbackuppath . $file;
                $temp[$num]['name'] = $file;
                $temp[$num]['size'] = @ceil(filesize($filename) / 1024);
                $temp[$num]['time'] = date("Y-m-d H:i:s", filemtime($filename));
                $temp[$num]['path'] = $filename;
                $num++;
            }
        }
    }
    return $temp;
}

function get_create_table($table) {
    global $conn, $variable, $db_database;
    $tsqlstr = "show create table $table";
    $trs = ii_conn_query($tsqlstr, $conn);
    $arr = ii_conn_fetch_all($trs);
    $ctable = array_values($arr)[0];
    $data .= get_table_structure($ctable);
    $data .= get_table_records($ctable['Table']);
    $data = rtrim($data, "\r\n\r\n")."\r\n\r\n";//先移除结尾的换行,再添加.避免如已存在时再添加,造成多余换行的情况
    return $data;
}

function genTitle() {
    $time = date("Y-m-d H:i:s", time());
    $str = "";
    $str .= "SET FOREIGN_KEY_CHECKS=0;\r\n\r\n";
    return $str;
}

function get_table_structure($ctable) {
    $str = "";
    $str .= $ctable['Create Table'] . ";\r\n\r\n";//获取创建表结构语句
    return $str;
}

function get_table_records($table) {
    global $conn, $variable, $db_database;
    $tsqlstr = "select * from $table";
    $trs = ii_conn_query($tsqlstr, $conn);
    $arr = ii_conn_fetch_all($trs);
    if (is_array($arr)) {
        $str = "";
        foreach ($arr as $val) {
            if (is_array($val)) {
                $valArr = array();
                foreach ($val as $k => $v) {
                    $valArr[] = "'" . str_replace(array(
                        "'",
                        "\r\n\r\n"
                    ) , array(
                        "\'",
                        "\\r\\n\\r\\n"
                    ) , $v) . "'";
                }
                $values = implode(', ', $valArr);
                $str .= "($values),";
            }
        }
        if(!ii_isnull($str)){
            $str = "INSERT INTO `" . $table . "` VALUES" . $str;
            $str = rtrim($str, ",") .";";//删除最后一个多余字符串并添加分号
        }
        return $str . "\r\n\r\n";
    }
    return "\r\n\r\n";
}

?>