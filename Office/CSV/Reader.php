<?php

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/9/2
 * Time: 13:48
 */
namespace Xjtuwangke\Office\CSV;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Closure;

class Reader
{

    /**
     * @var LexerConfig
     */
    protected $config;

    /**
     * @var bool
     */
    protected $unstrict = false;

    /**
     * @param array $config
     */
    public function __construct( array $config = array() ){
        $this->config = new LexerConfig();
        $this->config
            ->setDelimiter( array_get( $config, 'delimiter' , ";") ) // Customize delimiter. Default value is comma(,)
            ->setEnclosure( array_get( $config , 'enclosure' , "'" ))  // Customize enclosure. Default value is double quotation(")
            ->setEscape( array_get( $config , 'escape' , "\\" ))    // Customize escape character. Default value is backslash(\)
            ->setToCharset( array_get( $config , 'to_charset' , 'UTF-8' )) // Customize target encoding. Default value is null, no converting.
            ->setFromCharset( array_get( $config , 'from_charset' , 'UTF-8')) // Customize CSV file encoding. Default value is null.
        ;
        if( array_get( $config , 'unstrict' ) ){
            $this->unstrict = true;
        }
    }

    /**
     * @param $file
     * @param Closure|null $callback
     * @return array|null
     */
    public function import( $file , Closure $callback = null ){
        $interpreter = new Interpreter();
        if( $this->unstrict ){
            $interpreter->unstrict();
        }
        if( is_null( $callback ) ){
            $results = array();
            $callback = function( array $row ) use( &$results ){
                $results[] = $row;
            };
        }
        else{
            $results = null;
        }
        $interpreter->addObserver($callback);
        $lexer = new Lexer( $this->config );
        $lexer->parse( $file , $interpreter );
        return $results;
    }
}