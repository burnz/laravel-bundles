<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/11
 * Time: 00:18
 */

namespace Xjtuwangke\LaravelModels\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration as BaseMigration;
use Schema;

abstract class Migration extends BaseMigration
{
    protected $models = array();

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach( $this->models as $model ){
            Schema::create( $model::getTableName() , function( Blueprint $table )use( $model ){
                $model::_schema( $table );
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $models = array_reverse( $this->models );
        foreach( $models as $model ){
            Schema::dropIfExists( $model::getTableName() );
        }
    }
}