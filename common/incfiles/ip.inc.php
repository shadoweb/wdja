<?php
function mm_disable_ip() {
  //检测IP状态,进行相应处理
  if (mm_disable_ua()) {http_response_code(404);exit('非法IP,禁止访问!');}
  $tip = ii_get_client_ip();
  if($tip == '::1') $tip = '127.0.0.1';
  if (ii_isAdmin() && mm_search_field('expansion/iplock',$tip,'ip') && ip_get_field($tip,'out') == 0) ip_update_field($tip,'out');
  if (ii_isAdmin() && mm_search_field('expansion/iplock',$tip,'ip') && ip_get_field($tip,'robots') != 'admin') ip_update_field($tip,'robots');
  if ((ip_get_field($tip,'count') >= 50 || ip_get_field($tip,'lock') == 1) && ip_get_field($tip,'out') == 0) 
  {
    if (ip_get_field($tip,'lock') == 0) ip_update_field($tip,'lock');
    http_response_code(404);
  }else{
    if (!mm_search_field('expansion/iplock',$tip,'ip')) {
      ip_insert($tip);
    }elseif (!ii_isAdmin()) {
      ip_update_field($tip,'count');
    }
  }
}

function mm_disable_ua()
{
  $res = false;
  $ua = ii_getAllHeaders();
  $ua = $ua['User-Agent'];
  $forbid = 'Bytespider,Dotbot,Rogerbot,Crawler,Barkrowler,CakePHP,GarlikCrawler,Go-http-client,ias_crawler,ICC-Crawler,PotPlayer,Riddler,Scrapy,WINAMP,viz/viz,ZXing,Castro,Jakarta Commons,ltx71,NativeHost,SalesIntelligent,Xenu Link Sleuth,Y!J-ASR,BUbiNG,CRAZYWEBCRAWLER,http Cnrdn,Lavf,NSPlayer,spray-can,stagefright,voltron,LibVLC,A6-Indexer,crawler4j,wsr-agent,DigitalPebble Crawler,MBCrawler,AhrefsBot,GrapeshotCrawler,proximic,SemrushBot,ahoy!,alkaline,ananzi,anthill,arachnophilia,arale,araneo,aretha,ariadne,arks,askjeeves,atn worldwide,auresys,backrub,big brother,bjaaland,blackwidow,bloodhound,calif,cassandra,christcrawler.com,churl,cienciaficcion.net,cmc/0.01,collective,combine system,computingsite robi/1.0,crawler.feedback,cusco,cyberspyder link test,katalog/index,die blinde kuh,digger,direct hit grabber,download express,dwcp,ebiness,e-collector,emacs-w3 search engine,esculapio,esther,evliya celebi,fastcrawler,felix ide,fetchrover,fido,fish search,fouineur,freecrawl,funnelweb,gazz,gcreep,getterroboplus puu,geturl,golem,grapnel/0.01 experiment,griffon,gromit,Gluten,hämähäkki,harvest,havindex,hi (html index) search,hku www octopus,ht://dig,html_analyzer,htmlgobble,hyper-decontextualizer,ia_archiver,ibm_planetwide,image.kapsi.net,imagelock,incywincy,informant,infoseek sidewinder,ingrid,inktomi slurp,inspector web,intelliagent,internet shinchakubin,iron33,israeli-search,javabee,jcrawler,jumpstation,katipo,kdd-explorer,kilroy,kit-fireball,labelgrabber,larbin,legs,link validator,linkscan,linkwalker,lockon,logo.gif crawler,lycos,mac wwwworm,magpie,marvin/infoseek,mattie,mediafox,merzscope,mindcrawler,mnogosearch search engine software,moget,monster,motor,muncher,muninn,muscat ferret,mwd.search,nec-meshexplorer,nederland.zoek,netcarta webmap engine,netmechanic,netscoop,newscan-online,nhse web forager,nomad,northern light gulliver,nzexplorer,objectssearch,occam,OOZBOT,openfind data gatherer,orb search,pack rat,pageboy,parasite,patric,pegasus,perlcrawler 1.0,pgp key agent,phpdig,piltdownman,pioneer,plumtreewebaccessor,poppi,popular iconoclast,raven search,roadhouse crawling system,robofox,robozilla,rules,scooter,search.aus-au.com,searchprocess,senrigan,sg-scout,shagseeker,sift,site searcher,site valet,sitetech-rover,skymob.com,slcrawler,sleek,snooper,suke,suntek search engine,sven,sygol,tach black widow,tarantula,templeton,the peregrinator,the web moose,the web wombat,the world wide web wanderer,the world wide web worm,titan,titin,ucsd crawl,udmsearch,unnamed,url check,valkyrie,verticrawl,victoria,vision-search,voyager,w3m2,w3mir,walhello appie,wallpaper (alias crawlpaper),web core / roots,webcatcher,webcopy,webfetcher,webinator,weblayers,weblinker,weblog monitor,webmirror,webquest,webreaper,websnarf,webstolperer,webvac,webwalk,webwalker,webwatch,webzinger,wget,whatuseek winona,wild ferret web hopper,wired digital,wwwc ver,xget,daumoa,jobo,echo!,linkchecker,bloglines,twiceler,appie,sun4u,httrack,sisi,robi,webster pro,webster,zeus,scirus,picosearch,plucker,disco pump,gulliver,emailsiphon,teleport pro,fetch,pamuk,webcopier,webcapture,mass downloader,awv0.8dcrescent internet toolpak,webstripper,sitesucker,webdup,python-urllib,python,franklin locator,ck-sillydog,pockethttp,java,kototoi.org,teragramwebcrawler,vagabondo,nogoop-httpclient,myoperatb,myoperatb,accoona-ai-agent,arachmo,b-l-i-t-z-b-o-t,boitho.com-dc,cerberian drtrs,charlotte,converacrawler,cosmos,covario ids,dataparksearch,earthcom.info,fast enterprise crawler,fast-webcrawler,findlinks,g2crawler,holmes,htdig,iccrawler,ichiro,igdespyder,issuecrawler,l.webis,lwp-trivial,mabontland,magpie-crawler,mnogosearch,mogimogi,morning paper,mvaclient,netresearchserver,netseer crawler,newsgator,ng-search,nutchcvs,nymesis,oegp,orbiter,peew,pompos,postpost,pycurl,qseero,radian6,sandcrawler,sbider,scoutjet,scrubby,searchsight,seekbot,semanticdiscovery,sensis web crawler,shim-crawler,shopwiki,snappy,sqworm,stackrambler,teoma,tineye,truwogps,updated,vortex,vyu2,webcollage,websquash.com,wf84,womlpefactory,yacy,yahooseeker,yahooseeker-testing,yandeximages,yandexmetrika,yeti,yooglifetchagent,zyborg,wordpress,a6-indexer,wsr-agent,Microsoft Office,JDatabaseDriver,facebookexternalhit,The Knowledge AI,Twitterbot,VenusCrawler,aria2,GetCode,CCBot,NetTrack,Go-http-client,IAS crawler,POE-Component,VelenPublicWebCrawler,www.ru,Nutch Master Test,Wotbox,orion-semantics.com,lwp-request,ShortLinkTranslate,mj12bot,WinHttpRequest,Exabot,Auto Spider,Applebot,DuckDuckGo,SeznamBot,moatbot,DotBot,SurdotlyBot,28logsSpider,zgrab,Windows-Media-Player,spbot,Mail.RU_Bot,Backlink,SiteExplorer,SEOkicks,linkdexbot,Qwantify,DataXu,ExtLinksBot,gvfs/,evc-batch,Cliqzbot,YandexBot,YandexMobileBot,newspaper,Clickagy,Chicken laser,coccocbot,Microsoft Windows Network Diagnostics,spuhex.com,smtbot,Dataprovider,HybridBot,Sky-Wapproxy,SafeDNSBot,HatenaBookmark,Meta_Bot,ToutiaoSpider,HttpComponents,ips-agent,yandex.com/bots,(ziva),Jersey,Auto Shell Spider,User-Agent,curl/,MPlayer,internal request,Grammarly,package,TrendsmapResolver,PaperLiBot,startmebot,WebFuck,GStreamer,httpsrc,AntennaPod,panscient.com,webscan,Screaming Frog,WFilter Live,trendictionbot,nsrbot,PlurkBot,Mojolicious,AlphaBot,tracemyfile,VCTestClient,heritrix,MiniRedir,Iframely,rest-client,Cappuccino,FirmsBot,BOT for JCE,Nimbostratus-Bot,Emacs-w3m,WordupinfoSearch,Dispatch,Paracrawl,Mr.4x3,axios,Typhoeus,tools.random,WhatCMSBot,InetURL,NetpeakCheckerBot,Goose,lua-resty,WhatWeb,special_archiver,XoviBot,Wappalyzer,OK-Search-Bot,abot,Mechanize,uipbot,GnowitNewsbot,PostmanRuntime,HoneyBee,gobuster,Bidtellect,Sonos,RankingBot,Uptimebot,Synapse,Re-re Studio,Mappy,Statastico,Linguee Bot,PocketImageCache,colly,YunSecurityBot,archive.org_bot,CheckMarkNetwork';
  if (ii_search_str($ua,$forbid)) $res = true;
  return $res;
}

function ip_insert($ip,$admin = '')
{
  global $conn, $variable, $nlng;
  ii_conn_init();
  $ngenre = 'expansion/iplock';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tip = $ip;
  $tcome = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
  $trobots = 'unknown';
  $tout = 0 ;
  $tlock = 0 ;
  $tarea = mm_ip_map($tip);
  $tua = ii_getAllHeaders();
  $tua = $tua['User-Agent'];
  if (ii_isRobotsAgent()) {
    if (ii_search_str($tua,'bot') || ii_search_str($tua,'Spider') || ii_search_str($tua,'spider')) $trobots = 'robots';
    if (ii_search_str($tua,'Baiduspider')) $trobots = 'Baiduspider';
    if (ii_search_str($tua,'baiduboxapp')) $trobots = 'baiduboxapp';
    if (ii_search_str($tua,'Yahoo') || ii_search_str($tua,'Yahoo!') || ii_search_str($tua,'Slurp')) $trobots = 'Yahoo';
    if (ii_search_str($tua,'YandexBot')) $trobots = 'YandexBot';
    if (ii_search_str($tua,'360Spider')) $trobots = '360Spider';
    if (ii_search_str($tua,'bingbot')) $trobots = 'bingbot';
    if (ii_search_str($tua,'Sogou')) $trobots = 'Sogou';
    if (ii_search_str($tua,'Googlebot')) $trobots = 'Googlebot';
    if (ii_search_str($tua,'YisouSpider')) $trobots = 'YisouSpider';
    $tout = 1;
  }elseif (mm_disable_ua()) {
    $trobots = 'rubbish';
    $tlock = 1;
  }else{
    if (!ii_isnull($admin) || ii_isAdmin()) {
      $trobots = 'admin';
      $tout = 1;
    }
  }
  $ttime = ii_now();
  $tupdate = ii_now();
  $tcount = 1 ;
  $tsqlstr = "insert into $ndatabase (
    " . ii_cfnames($nfpre,'robots') . ",
    " . ii_cfnames($nfpre,'area') . ",
    " . ii_cfnames($nfpre,'ip') . ",
    " . ii_cfnames($nfpre,'come') . ",
    " . ii_cfnames($nfpre,'content') . ",
    " . ii_cfnames($nfpre,'lock') . ",
    " . ii_cfnames($nfpre,'out') . ",
    " . ii_cfnames($nfpre,'time') . ",
    " . ii_cfnames($nfpre,'update') . ",
    " . ii_cfnames($nfpre,'count') . ",
    " . ii_cfnames($nfpre,'lng') . "
    ) values (
    '" . ii_left(ii_cstr($trobots), 50) . "',
    '" . ii_left(ii_cstr($tarea), 50) . "',
    '" . ii_left(ii_cstr($tip), 50) . "',
    '" . ii_cstr($tcome) . "',
    '" . ii_left(ii_cstr($tua), 255) . "',
    '" . ii_get_num($tlock) . "',
    '" . ii_get_num($tout) . "',
    '$ttime',
    '$tupdate',
    '" . ii_get_num($tcount) . "',
    '$nlng'
    )";
  $trs = ii_conn_query($tsqlstr, $conn);
}

function ip_update_field($ip,$field)
{
  global $conn, $variable;
  ii_conn_init();
  $ngenre = 'expansion/iplock';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tip = $ip;
  $tupdate = ii_now();
  $tsqlstr = 'update '.$ndatabase.' set ';
  if ($field == 'count') $tsqlstr .= ii_cfnames($nfpre, "count") . '=' . ii_cfnames($nfpre, "count") . '+1,';
  if ($field == 'lock') $tsqlstr .= ii_cfnames($nfpre, "lock") . '= 1 ,';
  if ($field == 'out') $tsqlstr .= ii_cfnames($nfpre, "out") . '= 1 ,';
  if ($field == 'robots') $tsqlstr .= ii_cfnames($nfpre, "robots") . '= "admin" ,';
  $tsqlstr .= ii_cfnames($nfpre, "update") . '= "'.$tupdate.'" where ' . ii_cfnames($nfpre, "ip") . '="'.$tip.'"';
  ii_conn_query($tsqlstr, $conn);
}

function ip_get_field($ip,$field)
{
  global $conn, $variable;
  ii_conn_init();
  $ngenre = 'expansion/iplock';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tip = $ip;
  $tupdate = ii_now();
  $tcount = 0 ;
  $tsqlstr = 'select * from '.$ndatabase.' where ' . ii_cfnames($nfpre, "ip") . '="'.$tip.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $res =$trs[ii_cfnames($nfpre, $field)];
  }
  return $res;
}