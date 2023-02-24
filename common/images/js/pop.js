function pop_iframe(obj)
{
  get_id("iframe_" + obj).src = get_id("iframe_" + obj).getAttribute("data-src");
  get_id("pop_mask_" + obj).style.display = 'block';
  get_id("pop_page_" + obj).style.display = 'block';
  get_id("pop_mask_" + obj).className = 'popup_mask on';
  get_id("pop_page_" + obj).className = 'popup_page on';
}

function pop_iframe_close(obj)
{
  get_id("iframe_" + obj).src = '';
  get_id("pop_mask_" + obj).style.display = 'none';
  get_id("pop_page_" + obj).style.display = 'none';
  get_id("pop_mask_" + obj).className = 'popup_mask';
  get_id("pop_page_" + obj).className = 'popup_page';
}

function pop_del_li(obj)
{
  get_id(obj).parentNode.remove();
  var delid = obj.split('_')[1];
  var vid = obj.split('_')[0] + '_sid';
  var oval = get_id(vid).value;
  var oary = oval.split(',');
  var narys = [];
  for (var i = 0; i < oary.length; i++) {
    if(oary[i] != '' && oary[i] != null && oary[i] != delid) narys.push(oary[i]);
  }
  var nval = narys.join(',')
  get_id(vid).value = nval;
}

function pop_display(strid)
{
  get_id("pop_mask").style.display = 'block';
  get_id("pop_page").style.display = 'block';
  get_id("pop_add").style.display = 'block';
  get_id("pop_mask").className = 'popup_mask on';
  get_id("pop_page").className = 'popup_page on';
  get_id("strid").value = strid;
}

function pop_close()
{
  get_id("pop_mask").style.display = 'none';
  get_id("pop_page").style.display = 'none';
  get_id("pop_add").style.display = 'none';
  get_id("pop_edit").style.display = 'none';
  get_id("pop_mask").className = 'popup_mask';
  get_id("pop_page").className = 'popup_page';
}

function add_ok(strid){
    var file_title = get_id("file_title").value ;
    var file_desc = get_id("file_desc").value ;
    var file_url = get_id("file_url").value ;
    var opname = file_title ;
    var opvalue = file_title+"#:#"+file_desc+"#:#"+file_url ;
    if (file_title == "" || file_title.length == 0){
        alert(get_id("file_title").getAttribute("msg"));
    }
    else if (file_url == "" || file_url.length == 0){
        alert(get_id("file_url").getAttribute("msg"));
    }
    else{
        selects.add(get_id(strid), opname, opvalue);
        get_id("file_title").value = '';
        get_id("file_desc").value = '';
        get_id("file_url").value = '';
        alert(get_id("file_url").getAttribute("msgok"));
        pop_close();
    }
}

function edit_display(strers)
{
  get_id("pop_mask").style.display = 'block';
  get_id("pop_page").style.display = 'block';
  get_id("pop_edit").style.display = 'block';
  get_id("pop_mask").className = 'popup_mask on';
  get_id("pop_page").className = 'popup_page on';
}

function edit_img(strid, strvalue)
{
    if(strvalue == "" || strvalue == null || strvalue == undefined){
         alert(get_id("file_url").getAttribute("msgerr"));
    }else{
    edit_display();
    var file_array= new Array(); //定义一数组
    file_array = strvalue.split("#:#");;
    get_id("edit_title").value = file_array[0];
    get_id("edit_desc").value = file_array[1];
    get_id("edit_url").value = file_array[2];
    get_id("strid").value = strid;
    }
}

function edit_ok(strid){
    var file_title = get_id("edit_title").value ;
    var file_desc = get_id("edit_desc").value ;
    var file_url = get_id("edit_url").value ;
    var opname = file_title ;
    var opvalue = file_title+"#:#"+file_desc+"#:#"+file_url ;
    if (file_title == "" || file_title.length == 0){
        alert(get_id("edit_title").getAttribute("msg"));
    }
    else if (file_url == "" || file_url.length == 0){
        alert(get_id("edit_url").getAttribute("msg"));
    }
    else{
        selects.remove(get_id(strid));
        selects.add(get_id(strid), opname, opvalue);
        get_id("edit_title").value = '';
        get_id("edit_desc").value = '';
        get_id("edit_url").value = '';
        alert(get_id("edit_url").getAttribute("msgok"));
        pop_close();
    }
}

function insert_file(strid, strurl, strntype, strtype, strbase)
{
var tstrtype;
var file_arr = strurl.split("#:#");
var file_arr_len = file_arr.length - 1;
var file_url = file_arr[file_arr_len];
var file_title = file_arr[0];
var file_desc = file_arr[1];
  if (strtype == -1)
  {tstrtype = strntype;}
  else
  {
    var thtype = request["htype"];
    if (thtype == undefined)
    {tstrtype = strtype;}
    else
    {tstrtype = get_num(thtype);}
  }
  var file_type = get_file_type(strurl);
  switch (tstrtype)
  {
    case 0:
      if(file_type =='mp4' || file_type == 'avi' || file_type == 'webm' || file_type == 'ogg' || file_type == 'wmv' || file_type == 'm4v' || file_type == 'flv' || file_type == 'rm'){
        editor_insert(strid, "<p style=\"text-align: center;\"><video controls=\"controls\" style=\"width:85%;max-width:750px;margin:0 auto;\"><source src=\"" + file_url + "\" /></video></p>");
      }else if(file_type =='mp3' || file_type =='wav' || file_type =='wma' || file_type =='flac'){
         editor_insert(strid, "<p style=\"text-align: center;\"><audio controls=\"\" oncontextmenu=\"return false\" autoplay=\"\" controlslist=\"nodownload\" src=\"" + file_url + "\" />!audio not supported .</audio></p>");
      }else if(file_type =='jpg' || file_type =='png' || file_type =='gif' || file_type =='jpeg' || file_type =='bmp' || file_type =='webp'){
         editor_insert(strid, "<p style=\"text-align: center;\"><img src=\"" + file_url + "\" title=\"" + file_title + "\" alt=\"" + file_desc + "\" border=\"0\"></p>");
      }else{
        editor_insert(strid, "<p style=\"text-align: left;\"><strong><a href=\"" + file_url + "\" download>" + file_title + "</a></strong></p>");
      }
      break;
    case 3:
      itextner(strid,  "<img src=\"" + file_url + "\" border=\"0\">");
      break;
  }
}