var dict = {
    init: function (){
      var domain = document.domain;
      var port = window.location.port;
      var url = '//'+domain+':'+port+'/support/dict/api.php';
      var data = $.ajax({url:url,data:{},async:false});
      if(data.responseText){
        return JSON.parse(data.responseText);
      }
  }
}
var menuData = dict.init();

function createOptions(did,pid) {
    var s = '', item;
    for(var i = 0; i < menuData[did]['infos'].length; i++) {
        item = menuData[did]['infos'][i];
        if (menuData[did]['infos'][i].pid == pid) {
            s += '<option value="' + item.id + '">' + item.txt + '</option>';
        }
    }
    return s;
}

function menuChange(did,nameStr,pid,len){
 delKid(did,nameStr,len);
 var tdId = "";
 var d = document.all[nameStr];
 var tdLen = d.cells.length;
 var result = tdLen >= 1 && pid == 0 ? "" : createOptions(did,pid);
 if( result != "" ){
   tdId = d.insertCell(tdLen);
 }
 if(tdId != ""){
  if(tdLen == len) tdId.innerHTML='<select name="menuid" id="menuid'+pid+'" size="1" class="s1" ondblclick="sendResult(this.value)" onchange="menuChange(\''+did+'\',\''+nameStr+'\', this.value,'+(tdLen+1)+');"><option value=0>请选择分类</option>'+result+'</select>';
 }else{
  var btn = '&nbsp;&nbsp;<input type="hidden" name="dictid" value="'+pid+'"><a id="isbtn" class="isbtn" style="display: inline-block;width: 60px;height: 30px;line-height: 30px;margin-top: -2px;padding: 0;font-size: 14px;background-color: #5cb85c;border: 0;color: #fff;text-align: center;overflow: hidden;cursor: pointer;vertical-align: middle;" onclick="getParents(\''+did+'\',\''+pid+'\')">确定</a>';
  var tdId2 = d.insertCell(tdLen);
  tdId2.innerHTML += btn;
 }
}

function delKid(did,nameStr,len){
 if( len < 1 ) len = 1;
 var d = document.all[nameStr];
 while(d.cells.length > len) {
   d.deleteCell(d.cells.length-1);
 }
}

function readKid(did,pid) {
  document.write(createOptions(did,pid));
}


function viewKid(did,id) {
  if(id == 0) return document.write(readKid(did,id));
  var ids = new Array();
  ids = getParent(did,id).split(',');
  var idsLen = ids.length;
  var pid = 0;
  for( var i = 0; i < menuData[did]['infos'].length; i++ ) {
      if(menuData[did]['infos'][i].id == ids[0]) pid = menuData[did]['infos'][i].pid;
      if(menuData[did]['infos'][i].pid == pid ){
          get_id("menuid").options.add(new Option(menuData[did]['infos'][i].txt,menuData[did]['infos'][i].id));
      }
      if(menuData[did]['infos'][i].id == ids[0]){
          get_id("menuid").value = menuData[did]['infos'][i].id;
          menuChange(did,'menuTable',menuData[did]['infos'][i].id,1);
          for(var j=0;j<idsLen-1;j++){
              get_id("menuid"+ids[j]).value = ids[j+1];
              menuChange(did,'menuTable',ids[j+1],j+2);
          }
      }
  }
}


function getPid(did,id){
  var pid = 0;
  for(var i=0;i<menuData[did]['infos'].length;i++){
       if(menuData[did]['infos'][i].id == id ){
         return menuData[did]['infos'][i].pid;
       }
  }
}

function getTxt(did,id){
  var txt = '';
  for(var i=0;i<menuData[did]['infos'].length;i++){
       if(menuData[did]['infos'][i].id == id ){
         return menuData[did]['infos'][i].txt;
       }
  }
}

function getParent(did,id){
  var str = "";
  var idArr = new Array();
  var j = 0;
  for(var i=0;i<menuData[did]['infos'].length;i++){
       if( menuData[did]['infos'][i].id == id ){
         id = menuData[did]['infos'][i].pid;
         idArr[j++] = menuData[did]['infos'][i].id;
         i=0;
       }
  }
  if(id != 0) idArr[j++] = id;
  var len = idArr.length;
  while(len) {
    str += (str ? "," : "" )+idArr[len-1];
    len--;
  }
  return str;
}

function getParents(did,id){
  var str = "";
  var valArr = new Array();
  var j=0;
  for(var i=0;i<menuData[did]['infos'].length;i++){
       if( menuData[did]['infos'][i].id == id ){
         id = menuData[did]['infos'][i].pid;
         valArr[j++] = menuData[did]['infos'][i].txt;
         i=0;
       }
  }
  if(id != 0) valArr[j++] = getTxt(did,id);
  var len = valArr.length;
  while(len) {
    str += (str ? "" : "" )+valArr[len-1];
    len--;
  }
  var ins = get_id('menuTable').parentNode.parentNode.nextElementSibling;
  ins.value = str;
  get_id('isbtn').style.display='none';
  //return str;
}