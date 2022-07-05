String.prototype.Format = function(){
  var tmpStr = this;
  var iLen = arguments.length;
  for(var i=0;i<iLen;i++){
    tmpStr = tmpStr.replace(new RegExp("\\{" + i + "\\}", "g"), arguments[i]);
  }
  return tmpStr;
}
Calendar = {
  today      :  new Date(),
  year       :  2000,
  month      :  1,
  date       :  1,
  curPosX    :  0,
  curPosY    :  0,
  curCapture :  null,
  curDay     :  null,  

  display    :
    function(o, e, d){
      with(Calendar){
        o = typeof(o) == "object" ? o : document.getElementById(o);
        if(window.event){
          curPosX = document.body.scrollLeft + event.x;
          curPosY = document.body.scrollTop + event.y;
        } else{
          curPosX = e.pageX;
          curPosY = e.pageY;
        }
        if(o.value == "" && d) o.value = d;
        with(document.getElementById("Calendar__")){
          if(o != curCapture) {
            curCapture = o;
            if(style.display == "block"){
              style.left = curPosX + "px";
              style.top = curPosY + "px";
            }
            else load();
          }
          else{
            if (style.display == "block") style.display = "none";
            else load();
          } 
        }
      }
    },
  load:
    function(){
      with(Calendar){
        curDay = loadDate(curCapture.value);
        with(curDay){
          year = getFullYear();
          month = getMonth() + 1;
          date =   getDate();
        }
        init();
      }
    },
  init:
    function(){
      with(Calendar){
        with(new Date(year, month-1, date)){
          year = getFullYear();
          month = getMonth() + 1;
          date =    getDate();
          setDate(1);
          var first = getDay();
          setMonth(getMonth()+1, 0)
          paint(first, getDate());
        }
      }
    },
  paint:
    function(first, last){
      var calendar = document.getElementById("Calendar__");
      var grid = document.getElementById("dataGrid__");
      var i, l;
      l = Math.ceil((first + last)/7);
      if(!document.all){
        calendar.style.height = (41 + 19 * Math.ceil((first + last)/7)) + "px";
      }
      grid.innerHTML = new Array(l*7 + 1).join("<li><a></a></li>");
      with(Calendar){
        var strDate = "{0}-{1}".Format(year, month);
        var isTodayMonth = ((year == today.getFullYear()) && (month == today.getMonth() + 1));
        var isCurdayMonth = ((year == curDay.getFullYear()) && (month == curDay.getMonth() + 1));
        var todayDate = today.getDate();
        for(i=0;i<last;i++){
          grid.childNodes[first + i].innerHTML = '<a href="{2}-{1}"{0} onclick="Calendar.setValue({1});return false">{1}</a>'.Format(((i+1) == todayDate && isTodayMonth) ? ' class="today"' : isCurdayMonth && (i+1) == curDay.getDate()?' class="curDay"':'', i + 1, strDate);
        }
        document.getElementById("dateText__").innerHTML = '<a href="' + (year-1) + '年" onclick="Calendar.turn(-12);return false" title="上一年">&lt;&lt;</a> <a href="上一月" onclick="Calendar.turn(-1);return false" title="上一月">&lt;</a> ' + year + " - " + month + ' <a href="下一月" onclick="Calendar.turn(1);return false" title="下一月">&gt;</a> <a href="' + (year+1) + '年" onclick="Calendar.turn(12);return false" title="下一年">&gt;&gt;</a>';
        with(calendar){
          style.left = Calendar.curPosX + "px";
          style.top = Calendar.curPosY + "px";
          style.display = "block";
        }
      }
    },
  turn:
    function(num){
      Calendar.month +=  num;
      Calendar.date = 1;
      Calendar.init();
    },
  setValue:
    function(val){
      with(Calendar){
        curCapture.value = "{0}-{1}-{2}".Format(year, month, val);
        document.getElementById("Calendar__").style.display = "none";
      }
    },
  loadDate:
    function(op, formatString){
      formatString = formatString || "ymd";
      var m, year, month, day;
      switch(formatString){
        case "ymd" :
          m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
          if(m == null ) return new Date();
          day = m[6];
          month = m[5]*1;
          year =  (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
          break;
        case "dmy" :
          m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
          if(m == null ) return new Date();
          day = m[1];
          month = m[3]*1;
          year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
          break;
        default :
          break;
      }
      if(!parseInt(month)) return new Date();
      month = month==0 ?12:month;
      var date = new Date(year, month-1, day);
      return (typeof(date) == "object" && year == date.getFullYear() && month == (date.getMonth()+1) && day == date.getDate())?date:new Date();
      function GetFullYear(y){return ((y<30 ? "20" : "19") + y)|0;}
    },
    toString : function(){return false;}
}
var  __calendar_style = "<style>";
    __calendar_style += "#Calendar__ {background-color:#f6f6f6;width:240px;padding:5px;position:absolute;display:none;z-index:999;border:1px solid #}";
    __calendar_style += "#Calendar__ ul{list-style-type:none;margin:0;}";
    __calendar_style += "#Calendar__ ul li{display:block;width:32px;margin:1px;background-color:#fff;text-align:center;float:left;font:12px Aril;}";
    __calendar_style += "#Calendar__ ul li a{height:18px;display:block;background-color:#fff;line-height:18px;text-decoration:none;color:#666;}";
    __calendar_style += "#Calendar__ ul li a:hover{background:#336699;color:#FFF;}";
    __calendar_style += "#Calendar__ #dateText__{font:12px Aril;text-align:center;}";
    __calendar_style += "#Calendar__ #dateText__ a{font:10px Aril;color:#000;text-decoration:none;font-weight:bold;}";
    __calendar_style += "#Calendar__ #head__ li a{font:bold 12px Aril;}";
    __calendar_style += "#Calendar__ #dataGrid__{}";
    __calendar_style += "#Calendar__ #dataGrid__ li a:hover{background:#dedede;color:red;}";
    __calendar_style += "#Calendar__ #dataGrid__ .today{background:#f4f4f4;color:red;font-weight:bold;}";
    __calendar_style += "#Calendar__ #dataGrid__ .curDay{background:#dedede;color:blue;}";
    __calendar_style += "</style>";
    __calendar_style += "<div id=\"Calendar__\">";
    __calendar_style += "<div id=\"dateText__\"></div>";
    __calendar_style += "<ul id=\"head__\" onclick=\"return false\">";
    __calendar_style += "<li><a href=\"#\">Sun</a></li><li><a href=\"#\">Mon</a></li><li><a href=\"#\">Tue</a></li><li><a href=\"#\">Wed</a></li><li><a href=\"#\">Thu</a></li><li><a href=\"#\">Fri</a></li><li><a href=\"#\">Sat</a></li>";
    __calendar_style += "</ul>";
    __calendar_style += "<ul id=\"dataGrid__\"></ul>";
    __calendar_style += "</div>";
document.write(__calendar_style);