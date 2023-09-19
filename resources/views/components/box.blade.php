<div {{ $attributes->merge(['class' => 'small-box bg-' . $type]) }}>
    <div class="inner">
        <h3>{{ $number }}</h3>
        <h5>{{ $amount }}$</h5>
        <p>{{ $title }}</p>

    </div>
    <div class="icon">
        {!! $icon ?? '' !!}
    </div>
    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
