<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }} {!! $wrapper ?? '' !!}">
    <label for="{{$name ?? ''}}" class="control-label">{{$label ?? ''}}</label>
    <textarea class="form-control {{$class ?? ''}}" name="{{$name ?? ''}}" placeholder="{{$placeholder ?? ''}}" {!! $props ?? '' !!}>{{old($name, $value ?? '')}}</textarea>
    {!! $errors->first($name, '<p class="text-danger fs-12">:message</p>') !!}
</div>

