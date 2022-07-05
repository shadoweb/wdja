var ntags = new Array();
var ntagvalue = get_id('tagsHiddenInput').value;
if (ntagvalue != null) {
    ntags = ntagvalue.split(",");
    set_tags(ntags);
}

function del_arr(arr,val){
  var arr = arr.filter(function(item){
     return item != val;
 });
 return arr;
}

function get_tag(str) {
    var ntagvalue = get_id('tagsHiddenInput').value;
    if (ntagvalue != null) {
        ntags = ntagvalue.split(",");
    }
    ntags.push(str);
    ntags = clearAry(ntags);
    set_tags(ntags);
    get_id('tagsInput').focus();
}

function del_tag(str) {
    ntags = del_arr(ntags,str);
    set_tags(ntags);
    get_id('tagsInput').focus();
}

function set_tags(ntags) {
    var ntaghtml = '';
    var ntagvalue = '';
    for (var i = 0; i < ntags.length; i++) {
        if (ntags[i] != 'undefined' && ntags[i] != '' && ntags[i] != null) {
            ntaghtml += '<div class="tag-checked-name">' + ntags[i] + '<em onclick="del_tag(\'' + ntags[i] + '\')"></em></div>';
            ntagvalue += ntags[i] + ',';
        }
    }
    ntagvalue = ntagvalue.substring(0, ntagvalue.lastIndexOf(','));
    get_id('tagsInputs').innerHTML = ntaghtml;
    get_id('tagsInput').value = '';
    get_id('tagsHiddenInput').value = ntagvalue;
    if (get_id('tags_list')) get_id('tags_input').removeChild(get_id('tags_list'));
}

function clearAry(arr) {
    var arr = arr || [];
    var obj = {},
    res = [];
    for (var i = 0,
    ilen = arr.length; i < ilen; i += 1) {
        var curItem = arr[i],
        curItemType = typeof(curItem) + curItem;
        if (obj[curItemType] !== 1) {
            res.push(curItem);
            obj[curItemType] = 1;
        }
    }
    return res;
}

function get_tags(str,urls) {
    str = str.replace(/(^\s*)|(\s*$)/g,"");
    if (str != '' && str != null) {
        var url = urls + '/tags/api.php';
        var data = $.ajax({url:url,data:{q:str},async:false});
          if(data.responseText){
            var otags = JSON.parse(data.responseText);
                if (get_id('tags_list')) get_id('tags_input').removeChild(get_id('tags_list'));
                var dvleft = get_id('tagsInput').offsetLeft;
                var dvObj = '<div id="tags_list" style="width: 300px; border: 1px solid #4abee0; font-size:1.2rem;position: absolute;top:42px;left:' + dvleft + 'px;background:#fff;overflow:hidden;z-index:999;"></div>';
                get_id('tags_input').innerHTML = get_id('tags_input').innerHTML + dvObj;
                var $bool = true;
                var pObjs = '';
                for (var i = 0; i < otags.length; i++) {
                    var name = otags[i]['topic'];
                    if(name == str) $bool = false;//如果当前内容已存在对应标签,则不插入当前内容到标签列表
                    var pObj = '<p style="cursor: pointer; margin: 5px;" onclick="get_tag(this.innerText);">' + name + '</p>';
                    pObjs = pObjs + pObj;
                }
                var pStr = '<p style="cursor: pointer; margin: 5px;" onclick="get_tag(this.innerText);">' + str + '</p>';
                if($bool) get_id('tags_list').innerHTML = get_id('tags_list').innerHTML + pStr;
                get_id('tags_list').innerHTML = get_id('tags_list').innerHTML + pObjs;
                get_id('tagsInput').focus();
                get_id('tagsInput').value = str;
          } else {
            get_id('tagsInput').onmouseleave=function(){
                get_tag(str);
                str = '';
            }
            if (get_id('tags_list')) get_id('tags_input').removeChild(get_id('tags_list'));
          }
    } else {
        if (get_id('tags_list')) get_id('tags_input').removeChild(get_id('tags_list'));
    }
}