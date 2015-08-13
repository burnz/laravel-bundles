<div class="modal fade" id="<?=$id?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?=$id?>" aria-hidden="true" data-attr-field="<?=$field?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modal-label-<?=$id?>">请输入关键字</h4>
            </div>
            <div class="modal-body modal-body-search">
                <input class="modal-search-pattern" text="text" placeholder="请输入关键字">
            </div>
            <div class="modal-body modal-body-results" style="display:none;max-height:300px;overflow-y:scroll;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary btn-search-modal-search" id="btn-search-modal-<?=$id?>" data-attr-ajax-url="<?=URL::action($search_action)?>" data-csrf-token="<?=Session::getToken()?>">搜索</button>
                <button type="button" class="btn btn-warning btn-search-modal-reset" id="btn-reset-modal-<?=$id?>" style="display:none;">重置</button>
            </div>
        </div>
    </div>
</div>