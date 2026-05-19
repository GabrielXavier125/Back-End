@props(['status'])

@php
$config = match($status) {
    'waiting_teacher' => ['bg-blue-100 text-blue-800', 'Aguardando Professor'],
    'confirmed'       => ['bg-green-100 text-green-800', 'Confirmado'],
    'cancelled'       => ['bg-red-100 text-red-800', 'Cancelado'],
    default           => ['bg-gray-100 text-gray-800', $status],
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
    {{ $config[1] }}
</span>
