<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 22:39
 */

namespace Xjtuwangke\LaravelModels\Seeders\Fakers;

use Faker\Generator;

/**
 * Class Faker
 * @package Xjtuwangke\LaravelModels\Seeders\Fakers
 */
class Faker {

    static function factory(){
        $faker = new Generator();
        $faker->addProvider( new \Faker\Provider\zh_CN\Person( $faker ) );
        $faker->addProvider( new \Faker\Provider\zh_CN\Address($faker) );
        $faker->addProvider( new \Faker\Provider\zh_CN\PhoneNumber($faker) );
        $faker->addProvider( new \Faker\Provider\zh_CN\Company($faker) );
        $faker->addProvider( new \Faker\Provider\Lorem($faker) );
        $faker->addProvider( new \Faker\Provider\DateTime( $faker ) );
        $faker->addProvider( new \Faker\Provider\Internet( $faker ) );
        $faker->addProvider( new \Faker\Provider\Image( $faker ) );
        $faker->seed( rand( 0 , 65535 ) );
        return $faker;
    }

    /**
     * 一组fake数据
     * @param      $name
     * @param int  $count
     * @param bool $unique
     * @param null $faker
     * @return array
     */
    static function fakerList( $name , $count = 100 , $unique = true , $faker = null  ){
        if( null == $faker ){
            $faker = static::factory();
        }
        $results = [];
        if( $unique ){
            try{
                for( $i = 0 ; $i < $count ; $i++ ){
                    $results[] = $faker->unique()->$name;
                }
            }catch (\OverflowException $e) {
                die( "ERROR: faker could not generate $count unique $name(s)" );
            }
        }
        else{
            for( $i = 0 ; $i < $count ; $i++ ){
                $results[] = $faker->$name;
            }
        }
        return $results;
    }
}