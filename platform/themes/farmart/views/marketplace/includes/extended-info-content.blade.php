<div class="col-lg-12">
    <div class="row">
        @foreach ($availableSocials as $k => $name)
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label" for="socials_{{ $k }}">{{ $name }}</label>
                    {!! Form::url('socials[' . $k . ']', old('socials.' . $k, Arr::get($socials, $k, '')), [
                        'class' => 'form-control',
                        'id' => 'socials_' . $k,
                        'placeholder' => __('Enter link for :name', ['name' => $name]),
                        'maxlength' => 255,
                    ]) !!}
                    {!! Form::error('socials.' . $k, $errors) !!}
                </div>
            </div>
        @endforeach
    </div>
</div>
