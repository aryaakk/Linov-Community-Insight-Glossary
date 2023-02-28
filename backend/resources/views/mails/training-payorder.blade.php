@component('mail::message')
# Informasi pemesanan {{ $order->type }}
 
@component('mail::table')
|{{ $order->title }} (x {{ $order->quantity }})| Rp. {{ number_format($order->price * $order->quantity,0,',','.') }}  |
|:------------- |---------:|
|<b>Potongan Harga</b>| <b>Rp. {{ number_format($order->discount,0,',','.') }}</b> |
|<b>Sub Total</b>| <b>Rp. {{ number_format($order->total_price,0,',','.') }}</b> |
@endcomponent

Klik tombol di bawah untuk melihat detail pemesanan dan cara pembayaran.
@component('mail::button', ['url' => config('app.frontend_url') . '/training/order/detail/' . $order->id])
Detail pesanan
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent