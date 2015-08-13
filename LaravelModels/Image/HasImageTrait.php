<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 22:01
 */

namespace Xjtuwangke\LaravelModels\Image;

use Illuminate\Database\Eloquent\Collection;
use Xjtuwangke\StoredFile\Contracts\StoredImageContract;

trait HasImageTrait {

    /**
     * @var ImageModel
     */
    protected $imageModelInstance = null;

    /**
     * @return ImageModel
     */
    public function getImageModelInstance(){
        if( is_null( $this->imageModelInstance ) ){
            $this->imageModelInstance = new ImageModel();
        }
        return $this->imageModelInstance;
    }

    public function getImageModelClass(){
        return get_class( $this->getImageModelInstance() );
    }

    /**
     * @return mixed
     */
    public function images(){
        return $this->morphMany( $this->getImageModelClass() , 'imageable');
    }

    /**
     * @param string $type
     * @param int    $order
     * @return mixed
     */
    public function getImage( $type = 'default' , $order = 0 ){
        return $this->images()->ofType( $type )->where( 'order' , $order )->first();
    }

    /**
     * @param string $type
     * @param int    $limit
     * @return Collection
     */
    public function getImages( $type = 'default' , $limit = 20 ){
        return $this->images()->ofType( $type )->orderBy( 'order' , 'asc' )->take( $limit )->get();
    }

    /**
     * @param StoredImageContract $image
     * @param string              $type
     * @param int                 $order
     * @return ImageModel
     */
    public function setOneImage( StoredImageContract $image , $type = 'default' , $order = 0  ){
        $class = $this->getImageModelClass();
        $newImage = $class::firstOrCreate(array(
            'imageable_type' => $this->getMorphClass() ,
            'imageable_id' => $this->getKey() ,
            'type'  => $type ,
            'order' => $order ,
        ));
        $newImage->updateImageRecord( $image )->save();
        return $newImage;
    }

    /**
     * 批量设置多张图片
     * @param array $images
     * @param       $type
     * @return Collection
     */
    public function setMultipleImages( array $images , $type = 'multi' ){
        return \DB::transaction(function() use( $images , $type ){
            $class = $this->getImageModelClass();
            $class::where( 'imageable_type' , $this->getMorphClass() )
                ->where( 'imageable_id' , $this->getKey() )
                ->ofType( $type )
                ->delete();
            $results = array();
            foreach( $images as $order => $image ){
                $one = $class::insertImage(
                    $image ,
                    $this  ,
                    $type  ,
                    $order
                );
                $results[] = $one;
            }
            return $results;
        });
    }
}