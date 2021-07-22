<?php
$ary = api_timer_today();
$data = $ary['data'];
$num = $ary['num'];
$tgenre = 'expansion/timer';
$tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
$tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
$tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
if ($num>0) {
    foreach($data as $res) {
        if (ii_check_expireDate($res[$tfpre.'timer'])) {
            //这里判断定时任务类型进行相应操作:0发布,1隐藏
            $tevent = $res[$tfpre.'event'];
            switch($tevent) {
                case 0:
                    if(mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'hidden',0)){
                        if (mm_search_baidu(array('genre' => $res[$tfpre.'genre'],'gid' => $res[$tfpre.'gid']))) mm_baidu_push('update',$res[$tfpre.'genre'],mm_get_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'topic'),$res[$tfpre.'gid']);
                        else mm_baidu_push('urls',$res[$tfpre.'genre'],mm_get_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'topic'),$res[$tfpre.'gid']);
                        mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'time','"'.ii_now().'"');
                        mm_update_field($tgenre,$res[$tidfield],'timer_switch',0);
                        mm_update_field($tgenre,$res[$tidfield],'state',1);
                        mm_update_field($tgenre,$res[$tidfield],'update','"'.ii_now().'"');
                    }
                break;
                case 1:
                    if(mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'hidden',1)){
                        mm_baidu_push('del',$res[$tfpre.'genre'],mm_get_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'topic'),$res[$tfpre.'gid']);
                        mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'time','"'.ii_now().'"');
                        mm_update_field($tgenre,$res[$tidfield],'timer_switch',0);
                        mm_update_field($tgenre,$res[$tidfield],'state',1);
                        mm_update_field($tgenre,$res[$tidfield],'update','"'.ii_now().'"');
                    }
                break;
                default:
                    if(mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'hidden',0)){
                        if (mm_search_baidu(array('genre' => $res[$tfpre.'genre'],'gid' => $res[$tfpre.'gid']))) mm_baidu_push('update',$res[$tfpre.'genre'],mm_get_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'topic'),$res[$tfpre.'gid']);
                        else mm_baidu_push('urls',$res[$tfpre.'genre'],mm_get_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'topic'),$res[$tfpre.'gid']);
                        mm_update_field($res[$tfpre.'genre'],$res[$tfpre.'gid'],'time','"'.ii_now().'"');
                        mm_update_field($tgenre,$res[$tidfield],'timer_switch',0);
                        mm_update_field($tgenre,$res[$tidfield],'state',1);
                        mm_update_field($tgenre,$res[$tidfield],'update','"'.ii_now().'"');
                    }
                break;
            }
        }
    }
}
?>