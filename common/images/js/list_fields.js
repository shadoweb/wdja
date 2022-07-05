      var genre = document.getElementById('filterForm').getAttribute("genre");
      var list = document.getElementById('list_fields');
      var li = list.querySelectorAll('li');
      var listnow = GetRequest();
      for(var i = 0; i <li.length; i++){
        var option = li[i].getElementsByTagName("a");
        var strong = li[i].getElementsByTagName("strong");
        strong[0].className = "show";
        for(var j = 0; j < option.length; j++){
          var idvalue = option[j].getAttribute("id").split("|");
          document.getElementById(idvalue[0]).value = utils.getParam(genre + '_' + idvalue[0]);
          if(utils.getParam(genre + '_' + idvalue[0])==idvalue[1]){
            option[j].className = "show";
            strong[0].className = "";
          }
        }
      }

      function delClick(obj){
        var a = obj.parentNode.getElementsByTagName("a");
        for(var i = 0; i < a.length; i++){
          a[i].className = "";
          var idvalue = a[i].getAttribute("id").split("|");
          document.getElementById(idvalue[0]).value = 0;
          utils.delParam(genre + '_' + idvalue[0]);
        }
        document.getElementById("filterForm").submit();
      }

      (function(){
        var list = document.getElementById('list_fields')
        var li = list.querySelectorAll('li');
        var ids = [];
        for(var i = 0; i <li.length; i++){
          setClick(li[i],i);
        }
        var urlmore = location.search;//获取url中"?"符后的字串
        if(!urlmore) document.getElementById("filterForm").submit();
        function setClick(parent,index){
          var option = parent.getElementsByTagName("a");
          var strong = parent.getElementsByTagName("strong");
          for(var i = 0; i < option.length; i++){
            option[i].onclick = function(){
              strong[0].className = "";
              for(var i = 0; i < option.length; i++){
                option[i].className = "";
                var idvalue = option[i].getAttribute("id").split("|");
                utils.delParam(genre + '_' + idvalue[0]);
              }
              this.className = "show";
              var span = ids[index];
              if(ids[index]){
                var idvalue = this.getAttribute("id").split("|");
                document.getElementById(idvalue[0]).value = idvalue[1];
                document.getElementById("filterForm").submit();
                return;
              }
              var idvalue = this.getAttribute("id").split("|");
              utils.setParam(genre + '_' + idvalue[0],idvalue[1]);
              document.getElementById(idvalue[0]).value = idvalue[1];
              document.getElementById("filterForm").submit();
            };
          }
        }
      })();

function GetRequest() {
   var url = location.search;//获取url中"?"符后的字串  
   var theRequest = new Object();
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0; i < strs.length; i ++) {
          if(strs[i].split("=")[0] != 'type') utils.setParam(genre + '_' + strs[i].split("=")[0],unescape(strs[i].split("=")[1]));
      }
   }
}