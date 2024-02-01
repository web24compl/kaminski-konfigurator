@component('mail::message')
    Ktoś wypełnił ankietę<br/><br/>
    Mail: {{ $email }}

    Zaproponowany produkt: {{ $id }} - {{ $name }}
@endcomponent
