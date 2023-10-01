<div {{ $attributes->merge(['class' => 'small-box bg-' . $type]) }}>
    <div class="inner">
        <h3>{{ $number }}</h3>
        <h5>{{ $amount }}$</h5>
        <p>{{ $title }}</p>

    </div>
    @can('show invoice')
        <div class="icon">
            {!! $icon ?? '' !!}
        </div>
        <a href="{{ $link ?? '#' }}" class="small-box-footer">مزيد من التفاصيل <i class="fas fa-arrow-circle-right"></i></a>
    @endcan
</div>
