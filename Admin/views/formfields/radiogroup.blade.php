
<div {{ $field->formgroup() }}>
    <label for="{{ $field->name() }}" class="control-label">
        {{ $field->label() }}
    </label>
    <div class="row">
        @foreach( $field->options() as $key => $val )
        <div class="checkbox col-md-3">
            <label>
            <?php Debugbar::debug( $field->selected() )?>
            @if( ! is_null( $field->selected() ) &&  $key === $field->selected() )
            {{ Form::radio( $field->name() , $key , true  ) . $val }}
            @else
            {{ Form::radio( $field->name() , $key , false  ) . $val }}
            @endif
            </label>
        </div>
        @endforeach
    </div>
    <div class="text-danger">
        {{ $field->errors() }}
    </div>
</div>