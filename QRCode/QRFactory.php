<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/26
 * Time: 01:42
 */

namespace Xjtuwangke\QRCode;

use Config;
use Endroid\QrCode\QrCode as EndroidQRCode;

class QRFactory{

    /**
     * @param $code
     * @return QRCode
     * @throws \Xjtuwangke\BugSnag\Exception
     */
    public function make( $code ){
        $logo = Config::get('qrcode.logo');
        $level = Config::get('qrcode.level' , EndroidQRCode::LEVEL_HIGH );
        $qrcode = new QRCode( $code , $level );
        $qrcode->setLogo( $logo );
        return $qrcode;
    }
}