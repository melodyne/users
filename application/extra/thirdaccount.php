<?php
/**
 * 第三方账号配置
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/16
 * Time: 10:52
 */
use think\Request;
return [

    //奔格内微信小程序
    'wechat_applet'=>[
        'appid'                 =>  'wxbce6a412f0b48781',
        'secret'                =>  '79bfbf8a8ff763caadace596203a0f3b',
    ],

    //奔格内微信小程序支付
    'wechat_pay'=>[
        'app_id'                =>  'wx4884b031c452558f',  // 公众账号ID
        'mch_id'                =>  '1481498352',// 商户id
        'md5_key'               =>  'ki23bniu4u09hj9jh98220h08hs0h7uj',// md5 秘钥
        'notify_url'            =>  Request::instance()->domain().'/api/notify/wxpay',
        // 涉及资金流动时，需要提供该文件
        //'cert_path' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wx' . DIRECTORY_SEPARATOR . 'apiclient_cert.pem',
        //'key_path'  => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wx' . DIRECTORY_SEPARATOR . 'apiclient_key.pem',
    ],

    //支付宝支付
    'al_pay'=>[
        'use_sandbox'           =>  true,
        'partner'               =>  '2088102169252684',
        'app_id'                =>  '2016073100130857',
        'sign_type'             =>  'RSA2',
        'ali_public_key'        =>  'MIIBIjANBgkqhkiG9w0BAQEFAAOCAU3GYXkAaumdWQt7IDAQAB',
        'rsa_private_key'       =>  dirname(__FILE__) . DIRECTORY_SEPARATOR . 'rsa_private_key.pem',
        'limit_pay'             => [
            //'balance',
            //'moneyFund',
            // ... ...
        ],
        'notify_url'            =>  'https://helei112g.github.io/',
        'return_url'            =>  'https://helei112g.github.io/',
        'return_raw'            =>  false,
    ],

    //创蓝短信
    'chuang_lan'=>[
        'api_account'           =>  'N3490705',
        'api_password'          =>  'Ps309145'
    ],

    //腾讯云
    'qcloud'=>[
        'im_sdk'=>[
            'sdk_app_id'         =>  '1400011424',                               //sdk app id
            'identifier'         =>  'andmin',                                   //账号管理员
            'private_key_path'   =>  EXTEND_PATH."qcloud/imsdk/pem/private_key", // 独立模式密钥路径
            // 签名程序路径，这里请注意你的服务器操作系统，Linux、Windows，23位、64位选择对应的程序，Linux要注意权限
            'signature_path'     =>  EXTEND_PATH.'qcloud/imsdk/signature/linux-signature64'
        ]
    ]
];