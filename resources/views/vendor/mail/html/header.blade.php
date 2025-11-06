@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@elseif (trim($slot) === config('app.name'))
{{-- Custom logo for your app - you can change this URL to your own logo --}}
<img src="https://cdn-icons-png.flaticon.com/512/3003/3003984.png" class="logo" alt="{{ config('app.name') }}" style="height: 75px; max-height: 75px; width: 75px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
