@if( $field instanceof Xjtuwangke\KForm\FormField\Types\Text )
<div <?=$field->formgroup()?> >
    <label for="{{ $field->getFieldName() }}" class="control-label"><?=$field->getLabel()?></label>
    <input class="form-control" name="<?=$field->getFieldName()?>" placeholder="<?=$field->getPlaceholder()?>" type="text" value="<?=$field->getValue()?>" <?=$field->isFixed()?'readonly':'';?>>
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