<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 17:21
 */

namespace Xjtuwangke\KForm;

use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\FormField\Types\Text;
use Xjtuwangke\KForm\FormField\Types\TextArea;
use Xjtuwangke\KForm\FormField\Types\Password;
use Xjtuwangke\KForm\FormField\Types\Select;
use Xjtuwangke\KForm\FormField\Types\MultiSelect;
use Xjtuwangke\KForm\FormField\Types\AreaSelect;
use Xjtuwangke\KForm\FormField\Types\Checkbox;

class KFormFactory {

    const Text        = Text::class;

    const TextArea    = TextArea::class;

    const Password    = Password::class;

    const Select      = Select::class;

    const MultiSelect = MultiSelect::class;

    const Date        = 'TODO';

    const AreaSelect  = AreaSelect::class;

    const CheckBox    = CheckBox::class;

    /**
     * @param $type
     * @return \Xjtuwangke\KForm\FormField\FormField
     */
    public function field( $type ){
        return new $type;
    }

    /**
     * @param array $config
     * @return KForm
     */
    public static function build( array $config ){
        $form = new KForm();
        foreach( $config as $name => $blueprint ){
            $type = $blueprint['type'];
            $field = new $type;
            if( $field instanceof FormField ){
                $field->setFieldName( $name );
                $field->setLabel( $blueprint['label'] );
                $field->setRules( $blueprint['rules'] );
                if( isset( $blueprint['options'] ) ){
                    $field->setOptions( $blueprint['options'] );
                }
            }
            if( isset( $blueprint['width'] ) ){
                $field->setWidth( $blueprint['width'] );
            }
            $form->addFormField( $field );
        }
        return $form;
    }

}