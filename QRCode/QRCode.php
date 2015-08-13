<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/26
 * Time: 00:46
 */

namespace Xjtuwangke\QRCode;

use Endroid\QrCode\QrCode as EndroidQRCode;
use File;
use Xjtuwangke\BugSnag\Exception;
use Response;

class QRCode{

    /**
     * @var EndroidQRCode
     */
    protected $qrCode;

    /**
     * @var resource
     */
    protected $QR;

    /**
     * @var null | resource
     */
    protected $logo;

    public function __construct( $code , $level = EndroidQRCode::LEVEL_HIGH){
        $this->qrCode = new EndroidQRCode();
        $this->QR = $this->qrCode->setText( $code )
            ->setSize( 300 )
            ->setPadding( 10 )
            ->setImageType( EndroidQRCode::IMAGE_TYPE_JPEG )
            ->setErrorCorrection( $level )
            ->getImage();
    }

    /**
     * @caution $logo resource will be destroyed!
     * @param $logo resource | string
     * @throws Exception
     */
    public function setLogo( $logo = null ){
        if( is_null( $logo ) ){
            if( $this->logo ){
                imagedestroy( $this->logo );
                $this->logo = null;
            }
            return;
        }
        elseif( File::exists( $logo ) ){
            $logo = imagecreatefromstring( file_get_contents( $logo ) );
        }
        elseif( is_resource( $logo ) ){
            //do nothing
        }
        else{
            throw new Exception('logo file not found in QrCode:' . $logo );
        }
        if( $this->logo ){
            imagedestroy( $this->logo );
        }
        $this->logo = $logo;
    }

    protected function makeLogo(){
        if( $this->logo ){
            $QR_width = imagesx( $this->QR );
            $QR_height = imagesy( $this->QR );
            $logo_width = imagesx($this->logo);
            $logo_height = imagesy($this->logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($this->QR, $this->logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            $this->logo;
            $this->logo = null;
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function response(){
        return Response::stream(function(){
            imagejpeg( $this->QR );
        }, 200 , [ 'Content-Type' => 'image/jpeg' ]);
    }

    /**
     * @return resource
     */
    public function getImage(){
        return $this->QR;
    }

    /**
     *
     */
    public function __destruct(){
        if( $this->QR ){
            imagedestroy( $this->QR );
        }
        if( $this->logo ){
            imagedestroy( $this->logo );
        }
    }
}