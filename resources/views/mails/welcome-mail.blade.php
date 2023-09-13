<x-mail::message>
# اهلا بك في شركتنا {{ $user->name }}

## يرجى زيارة الصفحة التالية و ادخال كلمة المرور الخاصة بك

<x-mail::button :url="$url">
ادخال كلمة المرور
</x-mail::button>

شكرا<br/>
{{ config('app.name') }}
</x-mail::message>
