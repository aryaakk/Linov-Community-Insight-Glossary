@component('mail::message')
Hai,<br>
Terima kasih telah mendaftar di webinar "{{ $order->title }}" yang akan dilaksanakan hari {{ date('d M y',strtotime($order->schedule->date)) }} pukul {{ substr($order->schedule->start_hour,0,-3) }} WIB via Zoom:

Di webinar ini, Anda akan mendapatkan berbagai benefit berikut:<br>
📌 E-sertifikat<br>
📌 Relasi kerja<br>
📌 Insight bermanfaat seputar rekrutmen<br>
<br>
Link zoom akan dikirim H-2 ke email peserta.<br>
Pastikan untuk akses zoom 15 menit sebelum acara dimulai. See you!
 
@component('mail::subcopy')
Ada Pertanyaan?<br>
Hubungi +6282244078188
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent