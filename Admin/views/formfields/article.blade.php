
<div {{ $field->formgroup() }}>
    <label for="{{ $field->name() }}" class="control-label">{{ $field->label() }}</label>
    <div>
    <textarea form-data-role="wysiwyg" class="form-control" name="{{ $field->name() }}" rows="{{ $field->rows() }}">
    {{{ $field->value() }}}
    </textarea>
    </div>
    <div class="row">
        <input type="text" class="uploadify-result form-control" readonly>
        <input type="file" id="{{ '_uploadify-text-' . sha1( sprintf( "%08d" , rand( 0 , 99999999 ) ) . microtime() ) }}" form-role="image-upload-richtext" uploadify-swf-path="{{ Xjtuwangke\LaravelCms\KUrl::asset( 'uploadify/uploadify.swf' , null , false ) }}" uploadify-upload-url="{{ URL::action( 'uploadify.image' ) }}">
    </div>
    <div class="text-danger">
    </div>
</div>