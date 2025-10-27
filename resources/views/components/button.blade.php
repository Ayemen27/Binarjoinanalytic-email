<button {!! $attributes->merge([
    'class' => 'btn btn-primary btn-md ',
    'type' => 'submit',
]) !!}>
    {!! trim($slot) ?: translate('Save') !!}
</button>
