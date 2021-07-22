<?php
/**
 * PAYJS SDK For PHP
 * 单文件版本，有任何问题请直接提交issues
 * Github: https://github.com/payjs-cn/phpsdk
 * 2020.07 By PAYJS
 */

class Payjs
{
    protected $mchid;
    protected $key;
    protected $api_url_native;
    protected $api_url_cashier;
    protected $api_url_refund;
    protected $api_url_close;
    protected $api_url_reverse;
    protected $api_url_check;
    protected $api_url_user;
    protected $api_url_info;
    protected $api_url_complaint;
    protected $api_url_bank;
    protected $api_url_jsapi;
    protected $api_url_mweb;

    public function __construct($mchid, $key)
    {
        $this->mchid = $mchid;
        $this->key = $key;

        $api_url = 'https://payjs.cn/api/';
        $this->api_url_native    = $api_url . 'native';
        $this->api_url_cashier   = $api_url . 'cashier';
        $this->api_url_refund    = $api_url . 'refund';
        $this->api_url_close     = $api_url . 'close';
        $this->api_url_reverse   = $api_url . 'reverse';
        $this->api_url_check     = $api_url . 'check';
        $this->api_url_user      = $api_url . 'user';
        $this->api_url_info      = $api_url . 'info';
        $this->api_url_complaint = $api_url . 'complaint';
        $this->api_url_bank      = $api_url . 'bank';
        $this->api_url_jsapi     = $api_url . 'jsapi';
        $this->api_url_mweb      = $api_url . 'mweb';
    }

    // 扫码支付
    public function native(array $data)
    {
        $this->url = $this->api_url_native;
        return $this->post($data);
    }

    // JSAPI 模式
    public function jsapi(array $data)
    {
        $this->url = $this->api_url_jsapi;
        return $this->post($data);
    }

    // H5 模式
    public function mweb(array $data)
    {
        $this->url = $this->api_url_mweb;
        return $this->post($data);
    }

    // 收银台模式
    public function cashier(array $data)
    {
        $this->url = $this->api_url_cashier;
        $data      = $this->sign($data);
        $url       = $this->url . '?' . http_build_query($data);
        return $url;
    }

    // 退款
    public function refund($payjs_order_id)
    {
        $this->url = $this->api_url_refund;
        return $this->post($data);
    }

    // 关闭订单
    public function close($payjs_order_id)
    {
        $this->url = $this->api_url_close;
        return $this->post($data);
    }
    
    // 撤销订单
    public function reverse($payjs_order_id)
    {
        $this->url = $this->api_url_reverse;
        return $this->post($data);
    }

    // 检查订单
    public function check($data)
    {
        $this->url = $this->api_url_check;
        return $this->post($data);
    }

    // 用户资料
    public function user($openid)
    {
        $this->url = $this->api_url_user;
        $data      = ['openid' => $openid];
        return $this->post($data);
    }

    // 商户资料
    public function info()
    {
        $this->url = $this->api_url_info;
        $data      = [];
        return $this->post($data);
    }
    
    // 投诉查询
    public function complaint()
    {
        $this->url = $this->api_url_complaint;
        $data      = [];
        return $this->post($data);
    }

    // 银行资料
    public function bank($name)
    {
        $this->url = $this->api_url_bank;
        return $this->post($data);
    }

    // 异步通知接收
    public function notify()
    {
        $data = $_POST;
        if ($this->checkSign($data) === true) {
            return $data;
        } else {
            return '验签失败';
        }
    }

    // 数据签名
    public function sign(array $data)
    {
        $data['mchid'] = $this->mchid;
        $data = array_filter($data);
        ksort($data);
        $data['sign'] = strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $this->key)));
        return $data;
    }

    // 校验数据签名
    public function checkSign($data)
    {
        $in_sign = $data['sign'];
        unset($data['sign']);
        $data = array_filter($data);
        ksort($data);
        $sign = strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $this->key)));
        return $in_sign == $sign ? true : false;
    }

    // 数据发送
    public function post($data)
    {
        $data   = $this->sign($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'HTTP CLIENT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    }

}
