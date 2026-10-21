@php $timeline = $order->timeline; @endphp
<ol class="timeline-ssc">
    @foreach ($timeline['flow'] as $step)
        @php $state = $timeline['state'][$step] ?? 'todo'; @endphp
        <li class="{{ $state }}">
            <span class="dot">
                @if ($state === 'done')<i class="bi bi-check-lg"></i>
                @elseif ($state === 'current')<i class="bi bi-arrow-right-short"></i>
                @elseif ($state === 'cancelled')<i class="bi bi-x"></i>
                @else{{ $loop->iteration }}@endif
            </span>
            <span class="label">{{ \App\Models\Order::statusLabel($step) }}</span>
        </li>
    @endforeach
</ol>
