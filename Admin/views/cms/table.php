<?php if($table->title()):?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3><?=$table->title()?></h3>
            </div>
        </div>
    </div>
<?php endif;?>
<?=$table->draw()?>
<div class="container">
    <div class="center-block">
        <?=@$pagination?>
    </div>
</div>
<form id="_table-queries" style="display:none" action="" method="GET">
    <input name="sort" value="<?=@e($q['sort'])?>">
    <input name="order" value="<?=@e($q['order'])?>">
    <input name="search" value="<?=@e($q['search'])?>">
    <input name="field" value="<?=@e($q['field'])?>">
    <input name="groupby" value="<?=@e($q['groupby'])?>">
    <input name="group_value" value="<?=@e($q['group_value'])?>">
</form>