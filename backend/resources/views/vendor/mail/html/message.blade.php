@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
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

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<table>
    <tr>
       <td align="center"> © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')</td>
    </tr>
    <tr>
        <td align="center" style="font-size: 14px;"><small>Klik disini untuk <a href="#">Unsubscribe</a></small></td>
    </tr>
</table>
@endcomponent
@endslot
@endcomponent
