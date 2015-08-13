<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 21:08
 */

namespace Xjtuwangke\LaravelModels\Image;

use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\LaravelModels\BaseModel;
use Xjtuwangke\StoredFile\Contracts\StoredImageContract;

class ImageModel extends BaseModel{

    protected $table = 'images';

    public static function _schema_imageModel( Blueprint $table ){
        $table->string( 'image' );
        $table->string( 'url' );
        $table->unsignedInteger( 'order' )->default(0);
        $table->string( 'type' )->default('default');
        $table->integer( 'width' );
        $table->integer( 'height' );
        $table->morphs( 'imageable' );
        return $table;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable(){
        return $this->morphTo();
    }

    /**
     * @param        $query
     * @param string $type
     * @return mixed
     */
    public function scopeOfType( $query, $type = 'default' ){
        if( '*' == $type ){
            return $query;
        }
        return $query->where('type', $type);
    }

    /**
     * @param StoredImageContract $image
     * @return $this
     */
    public function updateImageRecord( StoredImageContract $image ){
        $this->image  = $image->getKey();
        $this->width  = $image->getWidth();
        $this->height = $image->getHeight();
        $this->url    = $image->getURL();
        return $this;
    }

    /**
     * @param StoredImageContract $image
     * @param BaseModel           $instance
     * @param string              $type
     * @param int                 $order
     * @return ImageModel
     */
    public static function insertImage( StoredImageContract $image , BaseModel $instance , $type = 'default' , $order = 0 ){
        $one = static::create(array(
            'imageable_type' => $instance->getMorphClass() ,
            'imageable_id'   => $instance->getKey() ,
            'type'           => $type ,
            'order'          => $order ,
        ));
        $one->updateImageRecord( $image )->save();
        return $one;
    }
}