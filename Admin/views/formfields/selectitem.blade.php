<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 00:06
 */
 $controller = $field->controllerClass();
 $id         = $field->itemId();
 $modal_id = Xjtuwangke\Random\KRandom::getRandStr( 32 , 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
 ?>
 <div {{ $field->formgroup() }}>
     <div class="row">
         <div class="picked-item-span">
         {{ $controller::display_picked_up_item( $id , $field->name() ) }}
         </div>
     </div>
     <label for="" class="control-label">{{ $field->label() }}</label>
     <div style="margin-top:20px;">
         <div>
             <div class="input-group">
                 <span class="input-group-btn">
                 <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#{{ $modal_id }}" href="javascript:;">选择</a>
                 </span>
             </div><!-- /input-group -->
         </div><!-- /.col-lg-6 -->
     </div>
     {{ $controller::display_search_modal( $modal_id , $field->name() ) }}
 </div>
