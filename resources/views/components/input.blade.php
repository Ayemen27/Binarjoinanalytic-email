@if ($label)
    <label class="form-label"
        @if ($id) for="{{ $id }}" @else for="{{ $name }}" @endif>{{ ucfirst($label) }}</label>
@endif

<input type="{{ $type }}" name="{{ $name }}"
    @if ($id) id="{{ $id }}" @else id="{{ $name }}" @endif
    value="{{ old($name, $value) }}"
    class="form-control form-control-md {{ $xClass }} @if ($showErrors) @error($name) is-invalid @enderror @endif"
    {!! $attributes !!} />

@if ($showErrors)
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            {{ $message }}
        </span>
    @enderror
@endif
