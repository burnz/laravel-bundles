<?php if( $item ):?>
    <?php
    $section_action = <<<BTN
<a class="btn btn-sm btn-danger" onclick="javascript:remove_parent_tr(this)" href="javascript:;"><span class="glyphicon glyphicon-minus"></span></a>
<input style="display:none" name="{$field}" type="text" value="{$item->id}">
BTN;
    $table = new KTable();
    $table->th( '订单号' )->setWidth( '10%' )
        ->th( '收件人' )->setWidth( '10%' )
        ->th('手机')->setWidth( '10%' )
        ->th( '地址' )->setWidth( '10%' )
        ->th( '状态' )->setWidth( '10%' )
        ->th( '操作' )->setWidth( '10%' )
        ->td( $item->order_no )
        ->td( $item->accept_name )
        ->td( $item->mobile )
        ->td( KTable::textInsideTd( $item->fullAddress(8) ) , [] , false )
        ->td( $item->status )
        ->td( $section_action , [] , false )
        ->tr();
    ?>
    <?=$table->draw()?>
<?php endif;?>