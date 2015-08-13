<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 22:41
 */

namespace Xjtuwangke\LaravelModels\Seeders;


use Illuminate\Database\Seeder;
use Xjtuwangke\LaravelModels\Seeders\Fakers\Faker;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

abstract class EloquentModelSeeder extends Seeder{

    /**
     * @var null faker
     */
    protected $faker = null;

    /**
     * 运行seeder
     */
    public function run(){
        \Eloquent::unguard();
        $this->faker = Faker::factory();
        $this->seed();
    }

    /**
     * @param      $name
     * @param int  $count
     * @param bool $unique
     * @return array
     */
    protected function fakeValues( $name , $count = 200 , $unique = true ){
        return Faker::fakerList( $name , $count , $unique , $this->faker );
    }

    public function csvInterpreter( $file , \Closure $closure ){
        $config = new LexerConfig();
        $config
            ->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
            ->setEnclosure("'")  // Customize enclosure. Default value is double quotation(")
            ->setEscape("\\")    // Customize escape character. Default value is backslash(\)
            ->setToCharset('UTF-8') // Customize target encoding. Default value is null, no converting.
            ->setFromCharset('UTF-8') // Customize CSV file encoding. Default value is null.
        ;
        $lexer = new Lexer(new LexerConfig());
        $interpreter = new Interpreter();
        $interpreter->addObserver( $closure );
        $lexer->parse( $file , $interpreter);
    }

    /**
     * seed实际操作
     * @return mixed
     */
    abstract protected function seed();
}