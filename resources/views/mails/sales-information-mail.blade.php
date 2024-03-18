@component('mail::message')
    Ktoś wypełnił ankietę<br/><br/>
    Mail: {{ $email }}
    Telefon: {{ $phone }}

    Zaproponowany produkt: {{ $id }} - {{ $name }}
@endcomponent
