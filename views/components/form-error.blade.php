@props(['field'])

@if ($errors->has($field))
    <div class="text-red-500 text-sm">
        @foreach ($errors->get($field) as $message)
            <div>{{ $message }}</div>
        @endforeach
    </div>
@endif
