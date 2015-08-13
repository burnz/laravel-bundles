<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:55
 */

namespace Xjtuwangke\LaravelModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\LaravelModels\QueryRequestHandler\QueryRequestHandlerTrait;


abstract class BaseModel extends Model{

    use SoftDeletes;

    use QueryRequestHandlerTrait;

    protected $dates = ['deleted_at'];

    protected $guarded = [ 'id' ];

    /**
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function _schema( Blueprint $table ){
        $class = get_called_class();
        $ref = new \ReflectionClass( $class );
        $methods = $ref->getMethods( \ReflectionMethod::IS_STATIC );
        $table->engine = 'InnoDB';
        $table->increments( 'id' );
        foreach( $methods as $method ){
            if( preg_match( '/^_schema_.*/' , $method->name ) ){
                $name = $method->name;
                $table = static::$name( $table );
            }
        }
        $table->softDeletes();
        $table->timestamps();
        return $table;
    }

    /**
     * @return string
     */
    public static function getTableName(){
        $model = new static;
        return $model->getTable();
    }
}