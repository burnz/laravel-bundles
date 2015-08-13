<?php if( $item ):?>
    <?php
    $section_action = <<<BTN
<a class="btn btn-sm btn-danger" onclick="javascript:remove_parent_tr(this)" href="javascript:;"><span class="glyphicon glyphicon-minus"></span></a>
<input style="display:none" name="{$field}" type="text" value="{$item->id}">
BTN;
        $table = new KTable();
        $table->th( '名称' )->setWidth( '10%' )
            ->th( '图片' )->setWidth( '10%' )
            ->th( '操作' )->setWidth( '10%' )
            ->td( $item->title )
            ->td( HTML::image( $item->getImage() ) , [] , false )
            ->td( $section_action , [] , false )
            ->tr();
    ?>
    <?=$table->draw()?>
<?php endif;?>