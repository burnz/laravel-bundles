
<div {{ $field->formgroup() }}>
    <div class="row">
        <label for="{{ $field->name() }}[]" class="control-label col-sm-2">
            {{ $field->label() }}
        </label>
        <div>
            @foreach( $field->options() as $key => $val )
            <div class="checkbox  col-sm-2">
                <label>
                @if( in_array( $key , $field->checked()) )
                {{ Form::checkbox( $field->name() . '[]' , $key , true  ) . $val }}
                @else
                {{ Form::checkbox( $field->name() . '[]' , $key , false  ) . $val }}
                @endif
                </label>
            </div>
            @endforeach
        </div>
    </div>
    <div class="text-danger row">
        {{ $field->errors() }}
    </div>
</div>