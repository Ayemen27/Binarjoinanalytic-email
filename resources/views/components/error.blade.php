@if (!empty($bag))
    @error($name, $bag)
        <div class="invalid-feedback d-block {{ $class }}">
            {{ $message }}
        </div>
    @enderror
@else
    @error($name)
        <div class="invalid-feedback d-block {{ $class }}">
            {{ $message }}
        </div>
    @enderror
@endif
