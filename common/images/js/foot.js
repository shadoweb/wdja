//幻灯js开始
var mySwiper = new Swiper ('.swiper-container', {
autoplay: {
  delay:3000,//秒            
  disableOnInteraction: false,//滑动不会失效
  reverseDirection: false,//如果最后一个 反向播放
},
loop: true,//轮播
followFinger: false,//手指滑动完毕在动
// 如果需要分页器
pagination: {
  el: '.swiper-pagination',
  clickable: true,
  renderBullet: function (index, className) {
    return '<span class="' + className + '">' + (index + 1) + '</span>';
  },
},

// 如果需要前进后退按钮
navigation: {
  nextEl: '.swiper-button-next',
  prevEl: '.swiper-button-prev',
},
});
//幻灯js结束

//菜单高亮js开始
  var curPageUrl = window.document.location.href;
  var rootPath = curPageUrl.split("//")[0] + '//';
  var rootUrl = new Array();
  if(curPageUrl.split("//")[1].length > 0) rootUrl = curPageUrl.split("//")[1].split("/");
  //console.log(rootUrl);
  for(i = 0; i<rootUrl.length - 1;i++){
   rootPath += rootUrl[i] + '/' ;
  }
  var menu = get_id("menu");
  var pobj = menu.getElementsByTagName('a');
  for(i = 0; i<pobj.length; i++){
    menuon(pobj[i], "mouseover", onMouseOver);
    menuon(pobj[i], "mouseout", onMouseOut);
    if(pobj[i].href + '/' == rootPath || pobj[i].href == rootPath) pobj[i].className = 'menu-link active';
    else pobj[i].className = 'menu-link';
  }
  function onMouseOver(e){
    clearClass();
    this.className = 'menu-link active';
  }
  function onMouseOut(e){
    clearClass();
    if(this.href + '/' == rootPath || this.href == rootPath){
      this.className = 'menu-link active';
    }else{
      menuon(menu, "mouseout", addClass);
    }
  }
  function addClass(){
    var pobj = menu.getElementsByTagName('a');
    for(i = 0; i<pobj.length; i++){
      if(pobj[i].href + '/' == rootPath || pobj[i].href == rootPath) pobj[i].className = 'menu-link active';
      else pobj[i].className = 'menu-link';
    }
  }
  function clearClass(){
    var pobj = menu.getElementsByTagName('a');
    for(i = 0; i<pobj.length; i++){
      pobj[i].className = 'menu-link';
    }
  }
  function menuon(element, type, callback) {
    if (element.addEventListener) {
      if (type.slice(0,2) === "menuon") type = type.slice(2);
      element.addEventListener(type, callback);
    } else {
      if (type.slice(0, 2) !== "menuon") type = "menuon" + type;
      element.attachEvent(type, callback);
    }
  }
//菜单高亮js结束