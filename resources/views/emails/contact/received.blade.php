@component('mail::message')
# Khách hàng liên hệ mới

Bạn nhận được một liên hệ mới với thông tin sau:

- **Họ:** {{ $contact['first_name'] ?? 'N/A' }}
- **Tên:** {{ $contact['last_name'] ?? 'N/A' }}
- **Email:** {{ $contact['email'] ?? 'N/A' }}
- **Số điện thoại:** {{ $contact['phone'] ?? 'N/A' }}

---

## Lời nhắn:
{{ $contact['message'] ?? 'Không có lời nhắn' }}

@component('mail::button', ['url' => route('client.home')])
Truy cập website
@endcomponent

Cảm ơn bạn,<br>
{{ config('app.name') }}
@endcomponent
