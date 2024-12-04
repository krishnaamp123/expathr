@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('storage/image/expatijo.png') }}" class="logo" alt="Laravel Logo" style="max-height: 50px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
