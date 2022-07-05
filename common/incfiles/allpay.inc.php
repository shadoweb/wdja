<?php
//支付类来源:https://github.com/zoujingli/WeChatDeveloper
require('AllPay/include.php');
function allpay_config($type) {
  global $alipay_appid,$alipay_public_key,$alipay_private_key,$alipay_notify_url,$alipay_return_url,$wxpay_token,$wxpay_appid,$wxpay_appsecret,$wxpay_encodingaeskey,$wxpay_mch_id,$wxpay_mch_key,$wxpay_ssl_key,$wxpay_ssl_cer,$wxpay_cache_path;
  ii_conn_init();
  $alipay_config = [
    'debug'       => true,
    'appid'       => $alipay_appid,
    'public_key'  => $alipay_public_key,
    'private_key' => $alipay_private_key,
    'notify_url'  => $alipay_notify_url,
    'return_url'  => $alipay_return_url,
  ];

  $wxpay_config = [
    'token'          => $wxpay_token,
    'appid'          => $wxpay_appid,
    'appsecret'      => $wxpay_appsecret,
    'encodingaeskey' => $wxpay_encodingaeskey,
    'mch_id'         => $wxpay_mch_id,
    'cache_path'     => $wxpay_cache_path,
  ];
  switch($type) {
    default:
    case 'alipay':
      return $alipay_config;
      break;
    case 'wxpay':
      return $wxpay_config;
      break;
  }
}

function alipay($out_trade_no,$subject,$total_amount,$body) {
  $config = allpay_config('alipay');
  try {

    // 实例支付对象
    if (ii_isMobileAgent()) $pay = We::AliPayWap($config);
    else $pay = We::AliPayWeb($config);
    // $pay = new \AliPay\Web($config);

    // 参考链接：https://docs.open.alipay.com/api_1/alipay.trade.page.pay
    //$torderid,$tid,$tprice,$tid,$methodname
    $result = $pay->apply([
      'out_trade_no' => $out_trade_no, // 商户订单号
      'total_amount' => $total_amount,    // 支付金额
      'subject'      => $subject, // 支付订单描述
      'body'      => $body, // 支付订单描述
    ]);

    return $result; // 直接输出HTML（提交表单跳转)

  } catch (Exception $e) {

    // 异常处理
    echo $e->getMessage();

  }

}

function alipay_notify() {
  $config = allpay_config('alipay');
  $bool = false;
  try {
    // 实例支付对象
    // $pay = \We::AliPayApp($config);
    // $pay = new \AliPay\App($config);
    $pay = We::AliPayApp($config);;

    $data = $pay->notify();
    if (in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
      // @todo 更新订单状态，支付完成
      $bool = true;
      //file_put_contents('notify.txt', "收到来自支付宝的异步通知\r\n", FILE_APPEND);
      //file_put_contents('notify.txt', '订单号：' . $data['out_trade_no'] . "\r\n", FILE_APPEND);
      //file_put_contents('notify.txt', '订单金额：' . $data['total_amount'] . "\r\n\r\n", FILE_APPEND);
    } else {
      $bool = false;
      //file_put_contents('notify.txt', "收到异步通知\r\n", FILE_APPEND);
    }
  } catch (Exception $e) {
    // 异常处理
    $bool = false;
    //echo $e->getMessage();
  }
  return $bool;
}

function alipay_return() {
  $config = allpay_config('alipay');
  $bool = false;
  try {
    // 实例支付对象
    // $pay = \We::AliPayApp($config);
    $pay = new \AliPay\App($config);
    //$pay = We::AliPayApp($config);;

    $data = $pay->notify();
    var_dump($data);
    if (in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
      // @todo 更新订单状态，支付完成
      $bool = true;
      //file_put_contents('notify.txt', "收到来自支付宝的异步通知\r\n", FILE_APPEND);
      //file_put_contents('notify.txt', '订单号：' . $data['out_trade_no'] . "\r\n", FILE_APPEND);
      //file_put_contents('notify.txt', '订单金额：' . $data['total_amount'] . "\r\n\r\n", FILE_APPEND);
    } else {
      $bool = false;
      //file_put_contents('notify.txt', "收到异步通知\r\n", FILE_APPEND);
    }
  } catch (Exception $e) {
    // 异常处理
    $bool = false;
    //echo $e->getMessage();
  }
  return $bool;
}

function wxpay($out_trade_no,$subject,$total_amount,$body) {
  $config = allpay_config('wxpay');
  $wechat = new \WeChat\Pay($config);
  global $wxpay_appid,$wxpay_notify;
  // 组装参数，可以参考官方商户文档
  // openid需登录后获取,这里未做正确配置
  $options = [
    'body'             => $body,
    'out_trade_no'     => $out_trade_no,
    'total_fee'        => $total_amount,
    'openid'           => $wxpay_appid,
    'trade_type'       => 'JSAPI',
    'notify_url'       => $wxpay_notify,
    'spbill_create_ip' => '127.0.0.1',
  ];

  try {

    // 生成预支付码
    $result = $wechat->createOrder($options);

    // 创建JSAPI参数签名
    $options = $wechat->createParamsForJsApi($result['prepay_id']);

    // @todo 把 $options 传到前端用js发起支付就可以了

  } catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

  }

}

function wxpay_notify() {
  $config = allpay_config('wxpay');
  $bool = false;
  try {

    // 3. 创建接口实例
    $wechat = new \WeChat\Pay($config);
    // $wechat = \We::WeChatPay($config);
    //$wechat = \WeChat\Pay::instance($config);

    // 4. 获取通知参数
    $data = $wechat->getNotify();
    if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS') {
      // @todo 去更新下原订单的支付状态
      $bool = true;
      //$order_no = $data['out_trade_no'];

      // 返回接收成功的回复
      //echo $wechat->getNotifySuccessReply();
    }

  } catch (Exception $e) {

    // 出错啦，处理下吧
    $bool = false;
    //echo $e->getMessage() . PHP_EOL;

  }
  return $bool;
}


function wxlogin()
{
  $config = allpay_config('wxpay');
  $redirect_uri = 'http://www.wdja.net/donate/?type=wxopenid';
  $state = md5("ws" . date("YmdH"));
  try {
    // 3. 创建接口实例
    $wechat = new \WeChat\Oauth($config);
    $url = $wechat->getOauthRedirect($redirect_uri, $state = $state, $scope = 'snsapi_userinfo');//snsapi_userinfosnsapi_base
    header('location:' . $url);
  } catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

  }
}

function wxlogin_openid()
{
  $config = allpay_config('wxpay');
  $res = $_REQUEST;
  if (empty($res['code'])) {
    die('获取Code失败');
  }
  try {

    // 3. 创建接口实例
    $wechat = new \WeChat\Oauth($config);
    $data = $wechat->getOauthAccessToken();
    $access_token = $data['access_token'];
    $openid = $data['openid'];
    if ($wechat->checkOauthAccessToken($access_token, $openid))
    {
      //$refresh_token = $access_token;
      //$wechat->getOauthRefreshToken($refresh_token);
      return $wechat->getUserInfo($access_token, $openid, $lang = 'zh_CN');
    }

  } catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

  }
}

//payjs支付开始
require('payjs.class.php');

function get_payjs_field($out_trade_no,$field)
{
global $conn;
global $ndatabase_order, $nidfield_order, $nfpre_order;
$tsqlstr = "select * from $ndatabase_order where ".ii_cfnames($nfpre_order,'out_trade_no')."=$out_trade_no";
$trs = ii_conn_query($tsqlstr, $conn);
$trs = ii_conn_fetch_array($trs);
if ($trs) return $trs[ii_cfnames($nfpre_order,$field)];
}

function payjs_notify()
{
    $data = $_POST;
    // 1.验签逻辑
    if ($data['return_code'] == 1) {
        // 2.验重逻辑
        // 3.自身业务逻辑
        $res = array();
        $res['data'] = $data;
        $res['state'] = 1;
    }else{
        $res['state'] = 0;
    }
        return $res;
}

function wxpayjs($out_trade_no,$subject,$total_amount,$body,$notify_url='',$callback_url='') {
    global $wxpayjs_id,$wxpayjs_key,$wxpayjs_notify;
    $mchid = $wxpayjs_id;
    $key = $wxpayjs_key;
    if($notify_url == '') $notify_url = $wxpayjs_notify;
    $total_amounts = $total_amount*100;
    $data = [
        "mchid" => $mchid,
        "total_fee" => $total_amounts,//金额。单位：分
        "out_trade_no" => $out_trade_no,//用户端自主生成的订单号
        "body" => $subject,//订单标题
        "notify_url" => $notify_url,
        "callback_url" => $callback_url,
    ];
    //更多参数https://help.payjs.cn/api/native.html
    $payjs = new Payjs($mchid, $key);
    if(ii_isMobileAgent()) $result = $payjs->mweb($data);
    else $result = $payjs->native($data);
    return $result;
}
?>