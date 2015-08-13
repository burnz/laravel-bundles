<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/4
 * Time: 22:57
 */

namespace Xjtuwangke\KForm\Dumper;

use Illuminate\Console\Command;

class FormDumpCommand extends Command{

    /**
     * @var string
     * @inheritdoc
     */
    protected $signature = 'form:dump';

    /**
     * @var array
     */
    protected $dumper_config = array();

    public function __construct( array $config ){
        $this->dumper_config = $config;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function handle(){
        foreach( $this->dumper_config as $class => $file ){
            if( \File::exists( $file ) ){
                if( ! $this->confirm("file {$file} exists, are you sure to OVERWRITE it? [y|N]")){
                    continue;
                }
                \File::delete( $file );
            }
            $instance = new $class;
            $config = FormDumper::dump( $instance );
            $php = var_export( $config , true );
            $now = date( 'Y-m-d H:i:s' , time() );
            $data = <<<DATA
<?php
/**
 * Created by FormDumper
 * Date: {$now}
 */

 return $php;
DATA;
            \File::append( $file , $data );
        }
    }
}