
<div {{ $field->formgroup() }}>
    <label for="{{ $field->name() }}[]" class="control-label">{{ $field->label() }}</label>
    <div class="row">
        <div form-role="multi-image-upload" uploadify-image-type="{{{ $field->type() }}}" uploadify-swf-path="{{ \Xjtuwangke\LaravelCms\KUrl::asset( 'uploadify/uploadify.swf' , null , false ) }}" uploadify-upload-url="{{ \URL::action( 'uploadify.image' ) }}" uploadify-field-name="{{ $field->name() }}[]">
            <div class="uploadify-multi-image-container">
                @foreach( $field->value() as $image )
                <div class="uploadify-image">
                    <img src="{{ $image }}" class="img-responsive uploadify-image">
                    <input value="{{ $image }}" name="{{ $field->name() }}[]" style="display:none">
                    <a href="javascript:;" class="uploadify-remove-image" onclick="$(this).parents('div.uploadify-image').remove();">x</a>
                </div>
                @endforeach
            </div>
            <input id="_uploadify-multi-image-{{ $field->name() }}" name="_uploadify" type="file">
        </div>
    </div>
     <div class="text-danger">
         {{ $field->errors() }}
     </div>
 </div>