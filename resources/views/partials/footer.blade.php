<footer>
    <div class="footer__head">
        <a href="{{route('home')}}" >
            <img src="{{ Vite::asset('resources/assets/logoAcademy.svg') }}" class="header__image">
        </a>
        <div class="footer__links">
            <a href="/" target="_blank">Informacje Prawne</a>
            <a href="/" target="_blank">Regulamin</a>
            <a href="/" target="_blank">Polityka prywatności</a>
        </div>
    </div>
    <div class="footer__info">
        <span>WSZELKIE PRAWA ZASTRZEŻONE  © <a href="/">{{config('app.name')}} {{now()->format('Y')}}</a></span>
        <span class="footer__web24">|  @lang('global.projectAndRealization')
            <a href="https://web24.com.pl/" target="_blank">
                <img src="{{ Vite::asset('resources/assets/web24logo.svg') }}" alt="Web24.com.pl">
            </a>
        </span>
    </div>
</footer>
