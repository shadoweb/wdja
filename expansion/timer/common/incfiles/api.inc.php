<?php
function api_timer_add() {
  //模块内容添加时调用,{$=api_timer_add()}
  $tmpstr = ii_itake('global.expansion/timer:manage.api_timer_add', 'tpl');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_timer_edit() {
  //模块内容编辑时调用,{$=api_timer_edit()}
  global $conn, $ngenre, $variable, $slng;
  $tgenre = 'expansion/timer';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $gid = $_GET['id'];
  $tmpstr = ii_itake('global.expansion/timer:manage.api_timer_edit', 'tpl');
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,"gid").'='.$gid.' and '.ii_cfnames($tfpre,"genre").'="'.$ngenre.'" and '.ii_cfnames($tfpre,"lng").'="'.$slng.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $ttimer = ii_get_date(ii_cstr($trs[ii_cfnames($tfpre,"timer")]));
  $timer = ii_format_date($ttimer,4);
  $tmpstr = str_replace('{$event}', $trs[ii_cfnames($tfpre,"event")], $tmpstr);
  $tmpstr = str_replace('{$timer_switch}', $trs[ii_cfnames($tfpre,"timer_switch")], $tmpstr);
  $tmpstr = str_replace('{$timer}', $timer, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_save_timer($id) {
  //模块内容入库时同步保存定时数据
  global $conn, $ngenre, $variable, $slng;
  $tgenre = 'expansion/timer';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $gid = $id;
  $switch = ii_get_num($_POST['timer_switch']);
  $event = ii_get_num($_POST['event']);
  $timer = ii_get_date(ii_cstr($_POST['timer']));
  $state = 0;
  if ($switch == 0) $state = 2;//state: 0进行中,1已完成,2已关闭
  if (ii_check_expireDate($timer)) {//当前定时已到期
      $switch = 0;//开关更改为已关闭
      $state = 1;//状态更改为已完成
    }
  if (!empty($gid) && $switch == 1) {
    $tsqlstr = "insert into $tdatabase (
	    	" . ii_cfnames($tfpre,'genre') . ",
	    	" . ii_cfnames($tfpre,'gid') . ",
	    	" . ii_cfnames($tfpre,'event') . ",
	    	" . ii_cfnames($tfpre,'timer_switch') . ",
	    	" . ii_cfnames($tfpre,'timer') . ",
	    	" . ii_cfnames($tfpre,'state') . ",
	    	" . ii_cfnames($tfpre,'time') . ",
	    	" . ii_cfnames($tfpre,'update') . ",
	    	" . ii_cfnames($tfpre,'lng') . "
	    	) values (
	    		'" . $ngenre . "',
	    		'" . $gid . "',
	    		'" . $event . "',
	    		'" . $switch . "',
	    		'" . $timer . "',
	    		'" . $state . "',
	    		'" . ii_now() . "',
	    		'" . ii_now() . "',
	    		'" . $slng . "'
	    	)";
    $trs = ii_conn_query($tsqlstr, $conn);
  }
}

function api_update_timer($id) {
  //模块内容编辑更新时同步更新定时数据
  global $conn, $ngenre, $variable;
  $tgenre = 'expansion/timer';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $gid = $id;
  $switch = ii_get_num($_POST['timer_switch']);
  $event = ii_get_num($_POST['event']);
  $timer = ii_get_date(ii_cstr($_POST['timer']));
  $state = 0;
  if ($switch == 0) $state = 2;//state: 0进行中,1已完成,2已关闭
  if (ii_check_expireDate($timer) && $switch != 0) {//当前定时已到期
      $switch = 0;//开关更改为已关闭
      $state = 1;//状态更改为已完成
      if ($event == 0 ) mm_update_field($ngenre,$gid,'hidden',0);
      else mm_update_field($ngenre,$gid,'hidden',1);
  }
  if (!api_timer_exist($ngenre,$gid)) api_save_timer($gid);
  if (!empty($gid)) {
    $tsqlstr = 'update '.$tdatabase.' set
	    ' . ii_cfnames($tfpre,'event') . '="' . $event . '",
	    ' . ii_cfnames($tfpre,'timer_switch') . '="' . $switch . '",
	    ' . ii_cfnames($tfpre,'state') . '="' . $state . '",
	    ' . ii_cfnames($tfpre,'timer') . '="' . $timer . '",
	    ' . ii_cfnames($tfpre,'update') . '="' . ii_now() . '"
	    where '.ii_cfnames($tfpre,'genre').'="'.$ngenre.'" and '.ii_cfnames($tfpre,'gid').'='.$gid;
    $trs = ii_conn_query($tsqlstr, $conn);
  }
}

function api_timer_exist($genre,$id) {
  //检查定时是否已存在
  global $conn, $ngenre, $variable;
  $bool = false;
  $tgenre = 'expansion/timer';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $gid = $id;
  $tsqlstr = 'select '.$tidfield.' from '. $tdatabase.' where '.ii_cfnames($tfpre,"gid").'='.$gid.' and '.ii_cfnames($tfpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $bool = true;
  return $bool;
}

function api_timer_today() {
  //检查定时是否有今天的任务
  //这里使用缓存,减轻数据库压力.同时加入最后缓存时间,过期删除重新缓存
  //判断时间差,到期则重新缓存
  global $nlng;
  $tgenre = 'expansion/timer';
  $tappstr = $tgenre.'_' . $nlng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
    $tdata = $GLOBALS[$tappstr];
    $ttime = $tdata['time'];//原缓存生成时间
    if (ii_check_expireDate($ttime,'30','5')) {
        //如果超过30分钟,则删除缓存,再重新生成后获取
        ii_cache_remove($tappstr);
        api_timer_today_putCache($tappstr);
    }
  }
  else api_timer_today_putCache($tappstr);
  return $GLOBALS[$tappstr];
}

function api_timer_today_putCache($tappstr) {
  global $conn, $variable, $nlng;
  $tgenre = 'expansion/timer';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tsqlstr = 'select *,DATEDIFF("'.ii_now().'",'.ii_cfnames($tfpre,"timer").') as daydiff from '.$tdatabase.' where '.ii_cfnames($tfpre,"timer_switch").'=1 and '.ii_cfnames($tfpre,"state").'=0';//今天开启的进行中任务
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_all($trs);
  $res_ary = array();
  $res = array();
  $i = 0;
  foreach($trs as $res) {
      if ($res['daydiff'] >= 0) {//再加上已到期的开启任务,=0今天的,>0到期的
          $res_ary[$i] = $res;
          $i++;
      }
  }
  $tres['data'] = $res_ary;
  $tres['num'] = $i;
  $tres['time'] = ii_now();
  ii_cache_put($tappstr, 1, $tres);
  $GLOBALS[$tappstr] = &$tres;
  unset($res_ary);
  unset($tres);
}