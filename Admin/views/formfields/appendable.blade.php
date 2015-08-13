
 <div {{ $field->formgroup() }}>
     <div class="row">
         <label for="{{ $field->name() }}" class="control-label col-lg-12 col-md-12" style="text-align:left;">{{ $field->label() }}</label>
     </div>
     <div class="appendable-inputs">
        <a href="javascript:;" class="btn btn-info btn-sm" form-role="appendable" data-attr-append-url="{{{ $field->append_url() }}}">增加一条</a>
        {{ $field->html() }}
     </div>
 </div>
