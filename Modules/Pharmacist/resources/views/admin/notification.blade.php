@extends('pharmacist::components.layouts.master')

@section('content')
    @php
        use Modules\Core\Models\Notification;

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();
        $unreadCount = $notifications->where('is_read', false)->count();
    @endphp
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title">الإشعارات</h2>
            <h5 class="cart-section-subtitle">عدد الإشعارات الغير مقروءة @if ($unreadCount > 0)
                    <b >
                        {{ $unreadCount }}
                    </b>
                @endif
            </h5>

        </div>

        <div class="cart-items">
            @if ($notifications->isEmpty())
            @else
                @foreach ($notifications as $item)
                    <a href="{{ route('notifications.read', $item->id) }}">
                        <div class="cart-item" style="background-color: {{ $item->is_read ? 'white' : '#ecf0fa' }} ;">
                            <div class="item-image"><i class="bi bi-bell"></i></div>

                            <div class="item-info">
                                <h4>{{ $item->title }}</h4>
                                <small class="text-muted d-block">
                                    {{ $item->body ?? '' }}
                                </small>

                            </div>

                            <div class="notification-subtext">
                                {{ $item->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

    </section>
@endsection
