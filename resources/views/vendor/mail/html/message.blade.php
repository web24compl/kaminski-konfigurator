@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ Vite::asset('resources/assets/logoAcademy.svg') }}" alt="{{ config('app.name') }}" style="max-width:40%;max-height: 100px;"/>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

Skontaktuj się z nami:<br>
Email: <a href='mailto:{{ nova_get_setting('contact_email') }}'>{{ nova_get_setting('contact_email') }}</a><br>
Telefon: <a href='tel:{{ nova_get_setting('contact_phone') }}'> {{ nova_get_setting('contact_phone') }}</a>

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<a target="_blank" href="{{url('/polityka-prywatnosci')}}">© {{ date('Y') }} {{ config('app.name') }}. Wszelkie prawa zastrzeżone.</a>
@endcomponent
@endslot
@endcomponent
