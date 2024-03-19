@component('mail::message')
Przerwano wypełnianie ankiety<br/><br/>
Mail: {{ $email }}<br/>
Telefon: {{ $phone }}<br/>

<table>
    <tr>
        <th>Rola</th>
        <th>Treść</th>
    </tr>
    @foreach($input as $item)
        <tr>
            <td>{{ $item['role'] }}</td>
            <td>{{ $item['content'] }}</td>
        </tr>
    @endforeach
</table>
@endcomponent
