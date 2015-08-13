<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 17:39
 */

namespace Xjtuwangke\KForm\Traits;

use Xjtuwangke\KForm\DataMapping\InstanceMappingWithFormContract;

trait FormMappingTrait {

    /**
     * @var array<InstanceMappingToFormContract>
     */
    protected $mappingInstances = array();

    public function addMappingInstance( InstanceMappingWithFormContract $mapping ){
        $this->mappingInstances[] = $mapping;
        return $this;
    }

    /**
     * 数据从instance映射到form
     */
    public function mapFromInstance(){
        foreach( $this->mappingInstances as $mapping ){
            foreach( $this->getFormFields() as $formField ){
                $mapping->instanceMapTo( $formField , $this );
            }
        }
    }

    /**
     * 数据从form映射到instance
     */
    public function mapToInstance(){
        foreach( $this->mappingInstances as $mapping ){
            foreach( $this->getFormFields() as $formField ){
                $mapping->instanceMapFrom( $formField , $this );
            }
        }
    }
}