<div class="mb-3">
    <label class="form-label" for="widget-name">{{ __('Name') }}</label>
    <input
        class="form-control"
        id="widget-name"
        name="name"
        type="text"
        value="{{ $config['name'] }}"
    >
</div>

<div
    class="border mb-2"
    style="max-height: 400px; overflow: auto"
>
    @for ($i = 1; $i <= 5; $i++)
        <div class="bg-light p-1">
            <div class="form-group mb-3">
                <label>{{ __('Title :number', ['number' => $i]) }}</label>
                <input
                    class="form-control"
                    name="data[{{ $i }}][title]"
                    type="text"
                    value="{{ Arr::get(Arr::get($config['data'], $i), 'title') }}"
                >
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Subtitle :number', ['number' => $i]) }}</label>
                <textarea
                    class="form-control"
                    name="data[{{ $i }}][subtitle]"
                    rows="3"
                >{{ Arr::get(Arr::get($config['data'], $i), 'subtitle') }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Icon :number', ['number' => $i]) }}</label>
                {!! Form::mediaImage('data[' . $i . '][icon]', Arr::get(Arr::get($config['data'], $i), 'icon')) !!}
            </div>
        </div>
    @endfor
</div>
