<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/6
 * Time: 13:29
 */

namespace Xjtuwangke\LaravelModels\Area;

use Xjtuwangke\LaravelModels\Seeders\EloquentModelSeeder;

class ChinaAreaSeeder extends EloquentModelSeeder
{
    protected $csv = 'areas.csv';

    /**
     * @inheritdoc
     */
    protected function seed(){
        \DB::table( China::getTableName() )->truncate();
        China::create(array(
            'areano' => '100000' ,
            'parentno' => '0' ,
            'name'   => '中国' ,
            'flag'   => '国家' ,
        ));
        $file = __DIR__ . '/' . $this->csv;
        $this->csvInterpreter( $file , function( $row ){
            China::insertArea( $row[0] , $row[1] , $row[2] );
        });
    }
}