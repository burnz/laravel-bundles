@if( $field instanceof Xjtuwangke\KForm\FormField\Types\Image )
<?php $id = '_upload-image-' . md5($field->getFieldName());$timestamp=time();?>
<div <?=$field->formgroup()?>>
    <label for="<?=$field->getFieldName()?>" class="control-label"><?=$field->getLabel()?></label>
    <div form-role="image-uploadive">
        <img src="<?=$field->getValue()?>" class="img-responsive uploadifive-image">
        <input hidden="hidden" class="hidden uploadifive-input" name="<?=$field->getFieldName()?>" type="text" value="<?=$field->getValue()?>">
        <input id="<?=$id?>" name="_uploadifive" type="file">
    </div>
    <div class="text-danger">
        <div class="text-danger">
            @foreach( $field->getErrors() as $error )
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    <script>
        $(function() {
            $('#<?=$id?>').uploadifive({
                'uploadScript' : '<?=url("upload/image")?>' ,
                'formData'     : {
                    '_token'    : '<?=csrf_token()?>' ,
                    'timestamp' : '<?=$timestamp?>',
                    'token'     : '<?=Hash::make($timestamp)?>'
                },
                'onUploadComplete' : function(file, data) {
                    eval('data=' + data);
                    var $img = $('#<?=$id?>').parents("div[form-role='image-uploadive']").children('img.uploadifive-image');
                    var $input = $('#<?=$id?>').parents("div[form-role='image-uploadive']").children('input.uploadifive-input');
                    if(data.url !== undefined){
                        $img.attr( 'src' , data.url );
                        $input.attr( 'value' , data.url);
                    }
                    else{
                        if( data.error !== undefined ){
                            alert( data.error );
                        }
                    }
                }
            });
        });
    </script>
</div>
@else
    <div>内部脚本错误</div>
@endif