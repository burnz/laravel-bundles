@if( $field instanceof \Xjtuwangke\KForm\FormField\Types\TextArea )
<div <?=$field->formgroup()?> >
    <label for="{{ $field->getFieldName() }}" class="control-label"><?=$field->getLabel()?></label>
    <textarea class="form-control" name="<?=$field->getFieldName()?>" placeholder="<?=$field->getPlaceholder()?>" rows="<?=$field->getRows()?>" <?=$field->isFixed()?'readonly':'';?>><?=$field->getValue()?></textarea>
    @if( $field->hasError() )
        <div class="text-danger">
            @foreach( $field->getErrors() as $error )
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>
@else
    <div>内部脚本错误</div>
@endif
