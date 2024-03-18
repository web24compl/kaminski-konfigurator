<div class="relative rounded-lg rounded-b-lg bg-gray-100 dark:bg-gray-800 bg-clip border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gray-100 dark:bg-gray-800 rounded-t-lg flex border-b border-gray-200 dark:border-gray-700">
        @foreach($items[0] as $key => $value)
            <div style="font-weight: bolder; @if($key == $shortKey) max-width: 10%; min-width: 10%; @endif" class="bg-clip flex-grow uppercase font-bold text-xxs text-gray-500 tracking-wide px-3 py-2 border-l border-gray-200 dark:border-gray-700" >{{$key}}</div>
        @endforeach
    </div>
    @foreach($items as $item)
    <div class="bg-gray-100 dark:bg-gray-800 rounded-t-lg flex border-b border-gray-200 dark:border-gray-700">
        @foreach($item as $key => $value)
        <div style="@if($key == $shortKey) max-width: 10%; min-width: 10%; @endif"  class="bg-clip flex-grow uppercase font-bold text-xxs tracking-wide px-3 py-2 border-l border-gray-200 dark:border-gray-700">
            {{$value}}
        </div>
        @endforeach
    </div>
    @endforeach
</div>
