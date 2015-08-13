@if( $field instanceof Xjtuwangke\KForm\FormField\Types\Select )
<div <?=$field->formgroup()?> >
    <label for="{{ $field->getFieldName() }}" class="control-label"><?=$field->getLabel()?></label>
    <?=Form::select( $field->getFieldName() , $field->getOptions() , $field->getValue() , [ 'form-role'=>'single-select' ] );?>
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