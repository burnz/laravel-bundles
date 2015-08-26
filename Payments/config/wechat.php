<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/22
 * Time: 19:08
 */

return array(
    'app_id' => env('WECHATPAY_APP_ID'),
    'app_secret' => env('WECHATPAY_APP_SECRET'),
    'pay_sign_key' => env('WECHATPAY_SIGN_KEY'),
    'partner' => env('WECHATPAY_PARTNER'), //商户平台登录帐号
    'partner_key' => env('WECHATPAY_PARTNER_KEY'), //商户平台登录密码
    'mch_id' => env('WECHATPAY_MCH_ID') , //微信支付商户号

    'cert_path'     => storage_path('cert/wechat/apiclient_cert.pem' ),
    'cert_key_path' => storage_path('cert/wechat/apiclient_key.pem') ,
);