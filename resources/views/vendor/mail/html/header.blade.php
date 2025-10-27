@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset(getSetting('logo')) }}" class="logo" alt="{{ @getSetting('site_name') }}">
        </a>
    </td>
</tr>
