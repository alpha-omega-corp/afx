{{-- Two guards, one field group.

     The question is the one a person sees. The text input below it is the one
     they never do: a bot fills every field it finds, a person cannot reach a
     field that is off the page and out of the tab order, so anything arriving
     with it filled did not come from a person. The honeypot costs the visitor
     nothing, which is why it carries most of the weight; the question catches
     what is left. --}}
<div class="app-captcha">
    <label class="app-captcha__question" for="{{ $id }}">
        {{ $question }}
        <span class="app-captcha__hint">{{ __('form.captcha_hint') }}</span>
    </label>

    <input
        class="form-control app-captcha__answer"
        type="text"
        id="{{ $id }}"
        name="captcha"
        inputmode="numeric"
        autocomplete="off"
        required
    >
</div>

<div class="app-captcha__trap" aria-hidden="true">
    <label for="{{ $trapId }}">{{ __('form.website') }}</label>
    <input type="text" id="{{ $trapId }}" name="website" tabindex="-1" autocomplete="off">
</div>
