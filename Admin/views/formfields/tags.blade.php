
<div {{ $field->formgroup() }}>
    <div class="row">
        <label for="{{ $field->name() }}" class="control-label col-lg-12 col-md-12" style="text-align:left;">{{ $field->label() }}</label>
    </div>
    <div class="row">
        <div class="tags-span col-lg-12 col-md-12">
        @foreach( $field->value() as $tag )
            <span class="label label-info" style="display:inline-block;margin-right:5px;">{{{ $tag }}}<a href="javascript:;" form-role="tag-remove" style="margin-left:10px;">x</a>
                <input style="display:none;" name="{{ $field->name() }}[]" value="{{{ $tag }}}">
            </span>
        @endforeach
        </div>
    </div>
    <div style="margin-top:20px;">
        <div class="">
            <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-btn">
                    <a class="btn btn-default" href="javascript:;" form-role="add-tag" form-attr-field="{{ $field->name() }}[]">增加标签</a>
                </span>
            </div>
        </div>
    </div>
</div>