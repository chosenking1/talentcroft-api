<div class="form-group mb-3 {{ $errors->has($name) ? 'has-error error' : '' }} {!! $wrapper ?? '' !!}">
    <label for="{{$name ?? ''}}" class="form-label">{!! $label ?? '' !!}</label>
    @if(isset($prepend) || isset($append))
        <div class="input-group">
            @isset($prepend)
                <span class="input-group-text" id="{{$name??''}}GroupPrepend">{!! $prepend ??'' !!}</span>
            @endif
            <input class="form-control {{$class ?? ''}}" name="{{$name ?? ''}}"
                   aria-describedby="{{$name??''}}GroupPrepend" id="{{$id ??$name ?? ''}}"
                   type="{{$type ?? 'text'}}" value="{{old($name, $value ?? '')}}"

                   placeholder="{{$placeholder ?? ''}}" {!! $props ?? '' !!}>
            @isset($append)
                <span class="input-group-text" id="{{$name??''}}GroupAppend">{!! $append ??'' !!}</span>
            @endisset
        </div>
    @else
        <input class="form-control {{$class ?? ''}}" name="{{$name ?? ''}}" id="{{$id ?? $name ?? ''}}"
               type="{{$type ?? 'text'}}" value="{{old($name, $value ?? '')}}"
               placeholder="{{$placeholder ?? ''}}" {!! $props ?? '' !!}>
    @endif

    {!! $extra ?? '' !!}
    @include('partials.error-message')
</div>

