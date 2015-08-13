@if( $field instanceof \Xjtuwangke\KForm\FormField\Types\Checkbox )
<div {{ $field->formgroup() }}>
    <div class="row">
        <label for="<?=$field->getFieldName()?>[]" class="control-label col-sm-2">
            <?=$field->getLabel()?>
        </label>
        <div>
            @foreach( $field->getOptions() as $key => $val )
                <div class="checkbox  col-sm-2">
                    <label>
                        <?=Form::checkbox( $field->getFieldName() . '[]' , $key , in_array( $key , $field->getValue())  ) . $val?>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="text-danger">
        <div class="text-danger">
            @foreach( $field->getErrors() as $error )
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
</div>
@else
<div>
    内部脚本参数错误
</div>
@endif