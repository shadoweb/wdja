var agt = navigator.userAgent.toLowerCase();
var isie = (agt.indexOf("msie")!= -1 && document.all);
var client_test;

if (document.getElementById)
{ client_test = "a"; }
else if (document.all)
{ client_test = "b"; }
else if (document.layers)
{ client_test = "c"; }

var request = new function()
{
  var iname,ivalue,icount;
  var urlstr = location.href;
  var inum = urlstr.indexOf("?")
  urlstr = urlstr.substr(inum + 1);
  var arrtmp = urlstr.split("&");
  for(icount = 0; icount < arrtmp.length; icount++)
  {
    inum = arrtmp[icount].indexOf("=");
    if(inum > 0)
    {
      iname = arrtmp[icount].substring(0, inum);
      ivalue = arrtmp[icount].substr(inum + 1);
      this[iname] = ivalue;
    }
  }
}

var xmlhttp = function()
{
  var xmlObj = null;
  if(window.XMLHttpRequest){
      xmlObj = new XMLHttpRequest();
  } else if(window.ActiveXObject){
      xmlObj = new ActiveXObject("Microsoft.XMLHTTP");
  } else {
      return;
  }
  return xmlObj;
}

function click_return(strt)
{
  var tmpvar = strt;
  var tmptrue = window.confirm(tmpvar);
  if (tmptrue) { return true; }
  return false;
}

function get_id(strname)
{
  switch (client_test)
  {
    case "a":
      return document.getElementById(strname);
      break;
    case "b":
      return document.layers[strname];
      break;
    default :
      return document.all(strname);
      break;
  }
}

function get_file_type(fileurl)
{
  var file_pre_length = fileurl.lastIndexOf(".");//取到文件名开始到最后一个点的长度
  var file_length = fileurl.length;//取到文件名长度
  var file_type = fileurl.substring(file_pre_length + 1, file_length );//截取获得后缀名
  return file_type;
}

function get_num(strers)
{
  if (isNaN(strers) || strers == "")
  {
    return 0;
  }
  else
  {
    return parseInt(strers);
  }
}

function get_sel_id(obj)
{
  var obj = arguments[0] ? arguments[0] : '';
  var frm = eval("document.sel_form");
  if (frm.sel_id.length)
  {
    var sel_ids = '';
    var slength = frm.sel_id.length;
    for (var i = 0; i < slength; i++)
    {
      if (frm.sel_id[i].checked)
      {
        if (sel_ids == '')
        {
          sel_ids = frm.sel_id[i].value;
        }
        else
        {
          sel_ids = sel_ids + ',' + frm.sel_id[i].value;
        }
      }
    }
  }
  else
  {
    if (frm.sel_id.value)
    {
      if (frm.sel_id.checked) sel_ids = frm.sel_id.value;
    }
  }
  if(obj == 'buy') localStorage.setItem('WdjaBuy',sel_ids);
  document.sel_form.sel_ids.value = sel_ids;
}

function get_selects_list(strid)
{
  var tobj = strid;
  if (tobj)
  {
    var ti,tstr;
    tstr = "";
    for (ti = 0; ti < tobj.options.length; ti ++)
    {
      if (tstr == "")
      {tstr = tobj.options[ti].value;}
      else
      {tstr += "|" + tobj.options[ti].value;}
    }
    return tstr;
  }
}

function iget(strers)
{
  var nxmlhttp = new xmlhttp();
  nxmlhttp.open("get", strers, false);
  nxmlhttp.send(null);
  return nxmlhttp.responseText;
}

function igets(strers, callback)
{
  var nxmlhttp = new xmlhttp();
  nxmlhttp.onreadystatechange = function()
  {
    if (nxmlhttp.readyState == 4)
    {
      if (nxmlhttp.status == 200 || nxmlhttp.status == 304)
      {
        callback(nxmlhttp.responseText);
      }
      else
      {
        callback("$error$")
      }
    }
  }
  nxmlhttp.open("get", strers, true);
  nxmlhttp.send(null);
}

function igets_xml(strers, callback)
{
  var nxmlhttp = new xmlhttp();
  nxmlhttp.onreadystatechange = function()
  {
    if (nxmlhttp.readyState == 4)
    {
      if (nxmlhttp.status == 200 || nxmlhttp.status == 304)
      {
        callback(nxmlhttp.responseXML);
      }
      else
      {
        callback("$error$")
      }
    }
  }
  nxmlhttp.open("get", strers, true);
  nxmlhttp.send(null);
}

function itextner(strid, strers)
{
  var tobj;
  tobj = get_id(strid);
  if (isie)
  {
    tobj.focus();
    document.selection.createRange().text = strers;
  }
  else
  {
    tobj.focus();
    tobj.value += strers;
  }
}

function iresize(stro, stra, strv)
{
  switch(stra)
  {
    case 1:
      if (stro.width > strv) stro.width = strv;
      break;
    case 2:
      if (stro.height > strv) stro.height = strv;
      break;
    default:
      if (stro.width > strv) stro.width = strv;
  }
}

function location_href(strers)
{
  var tburl = strers;
  var tbbase = get_id("base");
  if (tbbase)
  {
    var tbhref = get_id("base").href;
    if (tbhref) tburl = tbhref + tburl;
  }
  location.href = tburl;
}

function nhrefstate()
{
  var nhref = request["hspan"];
  if(!nhref == "")
  {
    var nhrefobj = get_id(nhref);
    if (nhrefobj)
    {
      nhrefobj.className = "on";
    }
  }
}

function nll(strers)
{}

function pop_win(strurl, strname, strwidth, strheight, strscroll)
{
  var nwidth = strwidth;
  var nheight = strheight;
  var leftsize = 0;
  var topsize = 0;
  if (nwidth == 0 || nheight == 0)
  {
    nwidth = screen.width - 8;
    nheight = screen.height - 55;
  }
  else
  {
    leftsize = (screen.width) ? (screen.width - nwidth)/2 : 0;
    topsize = (screen.height) ? (screen.height - nheight)/2 : 0;
  }
  window.open(strurl, strname, 'width=' + nwidth + ',height=' + nheight + ',left=' + leftsize + ',top=' + topsize + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=' + strscroll + ',resizable=no' );
}

function switch_display(obj,strers)
{
  var tobj = get_id(strers);
  var pobj = get_id("lists").getElementsByTagName('dl');
  var sobj = get_id("lists").getElementsByTagName('span');
  if(tobj.className == '')
  {
   for(var i = 0; i<pobj.length; i++){
        pobj[i].className = '';
    }
   for(var j = 0; j<sobj.length; j++){
        if(sobj[j].className == 'tit t1 open') sobj[j].className = 'tit t1';
    }
    obj.className = 'tit t1 open';
    tobj.className = 'open';
  }
    else
  {
    obj.className = 'tit t1';
    tobj.className = '';
  }
  
  var tcontainer = get_id("container");
  var ttopbar = get_id("topbar");
  var myWidth = document.body.scrollWidth - 230; 
  document.getElementById('main').style.cssText = 'width:'+myWidth+'px;height:'+(getClientHeight() - ttopbar.offsetHeight) + 'px';
  tcontainer.style.height = (getClientHeight() - ttopbar.offsetHeight) + 'px';
  
}

function switch_display_a(strers)
{
  var tlists = get_id("lists");
  var pobj = strers.parentNode.parentNode.parentNode;
  if (tlists.className == "leftmenu min")
  {
  pobj.className = '';
  }
}

function switch_display_default(strers)
{
  var tobj = get_id(strers);
  if(tobj.style.display == 'none')
  {
    tobj.style.display = '';
  }
    else
  {
    tobj.style.display = 'none';
  }
}

function iframe_onload(strers)
{
  var tsrc = strers.contentWindow.location.href;
  var tasrc = get_id("lists").getElementsByTagName('a');
   for(var i = 0; i<tasrc.length; i++){
        if(tasrc[i].href == tsrc) tasrc[i].parentNode.className = "tit t2 on";
        else tasrc[i].parentNode.className = "tit t2";
    }
    if(tsrc.indexOf("?site_language=") != -1) window.location.reload();
}
 
function select_all()
{
  var frm = eval("document.sel_form");
  if (frm.sel_id == null) {return false;}
  var sall = frm.sel_all.checked;
  if(sall){
    frm.sel_alls.checked = true;
    if (frm.sel_id.length)
    {
      var s1 = frm.sel_id.length;
      for (var i = 0; i < s1; i++) { frm.sel_id[i].checked = true; }
     }else{ frm.sel_id.checked = true; }
   }else{
    frm.sel_alls.checked = false;
    if (frm.sel_id.length)
    {
      var s2 = frm.sel_id.length;
      for (var j = 0; j < s2; j++) { frm.sel_id[j].checked = false; }
     }else{ frm.sel_id.checked = false; }                     
   }
}
 
function select_alls()
{
  var frm = eval("document.sel_form");
  if (frm.sel_id == null) {return false;}
  var salls = frm.sel_alls.checked;
  if(salls){
    frm.sel_all.checked = true;
    if (frm.sel_id.length)
    {
      var s1 = frm.sel_id.length;
      for (var i = 0; i < s1; i++) { frm.sel_id[i].checked = true; }
     }else{ frm.sel_id.checked = true; }
   }else{
    frm.sel_all.checked = false;
    if (frm.sel_id.length)
    {
      var s2 = frm.sel_id.length;
      for (var j = 0; j < s2; j++) { frm.sel_id[j].checked = false; }
     }else{ frm.sel_id.checked = false; }                     
   }
}

function changeAddress(id){
get_id('name').value = '';
get_id('address').value = '';
get_id('code').value = '';
get_id('phone').value = '';
get_id('email').value = '';
if(id != 0){
  var domain = document.domain;
  var port = window.location.port;
  var url = '//'+domain+':'+port+'/user/address/api.php?id=' + id;
  var ajax = createXMLHttpRequest();
  ajax.open('get',url);
  ajax.send(null);
  ajax.onreadystatechange = function () {
     if (ajax.readyState==4 &&ajax.status==200) {
       //console.log(ajax.responseText);
          var rtext = JSON.parse(ajax.responseText);
          get_id('name').value = rtext['name'];
          get_id('address').value = rtext['address'];
          get_id('code').value = rtext['code'];
          get_id('phone').value = rtext['phone'];
          get_id('email').value = rtext['email'];
      }
  }
}
}

function createXMLHttpRequest() {
        var xmlHttp;
        try{
            xmlHttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try{
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e){
                alert("Your browser does not support AJAX！");
                }
            }
        }            
        return xmlHttp;
    }


//获取URL参数
function GetRequest() {
   var url = location.search; //获取url中"?"符后的字串  
   var theRequest = new Object();  
   if (url.indexOf("?") != -1) {  
      var str = url.substr(1);  
      strs = str.split("&");  
      for(var i = 0; i < strs.length; i ++) {  
         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
      }  
   }
   return theRequest;  
}

function insert_images2(strid, strurl, strntype, strtype, strbase)
{
  var tstrtype;
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
  switch (tstrtype)
  {
    case 0:
      editor_insert(strid, "<img src=\"" + strurl + "\" border=\"0\" data-mce-src=\"" + strurl + "\">");
      break;
    case 1:
      itextner(strid, "[img]" + strurl + "[/img]");
      break;
    case 3:
      itextner(strid,  "<img src=\"" + strurl + "\" border=\"0\">");
      break;
  }
}

function inputSwitch(obj){
    var thisObj = obj;
    var thisVal = thisObj.getElementsByTagName('input')[0];
    if (thisObj.getAttribute('bind') == '1')
    {
    	if(thisVal.value == 0){
		    thisObj.className = 'switch switch-1';
		    thisVal.value = 1;
    	}else{
		    thisObj.className = 'switch';
		    thisVal.value = 0;
    	}
    }else{
	    thisObj.setAttribute("bind","1");
    }
}

function jscheck_topics(strers)
{
  var tstrers = strers;
  if (tstrers == "1") get_id("view_topic").style.display = "inline-block"; 
  else get_id("view_topic").style.display = "none";
}

function jscheck_topic(strtopic,strid)
{
  var strid = arguments[1] ? arguments[1] : '';
  if (strtopic == "") return false;
  if (strid == "") igets("manage.php?type=check_topic&topic=" + strtopic, jscheck_topics);
  else igets("manage.php?type=check_topic&topic=" + strtopic + "&id=" + strid, jscheck_topics);
}

function setTab(name,cursel,n){
    for(i=1;i<=n;i++){
        var menu=document.getElementById(name+i);
        var con=document.getElementById("con_"+name+"_"+i);
        menu.className=i==cursel?"hover":"";
        con.className=i==cursel?"hover":"";
        con.style.display=i==cursel?"block":"none";
    }
}