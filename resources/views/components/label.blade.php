@if ($name)
    <label class="form-label" {!! $attributes !!}>
        {{ ucfirst($name) }} @if ($attributes->get('required'))
            <span class="red">*</span>
        @endif
    </label>
@endif
