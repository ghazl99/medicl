@extends('pharmacist::components.layouts.master')
@section('css')
@endsection
@section('content')
    <section class="profile-section">
        <div class="profile-info">
            <h3 class="text-white">{{ Auth::user()->name }}</h3>
            <p>{{ Auth::user()->phone }}</p>
        </div>
        <div class="profile-actions">
            <h4>إعدادات الحساب
            </h4>
            <a href="{{ route('edit.profile.user') }}" class="profile-btn">
                <span>تعديل الملف الشخصي</span>
                <i class="bi bi-pencil-square"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="profile-btn logout">
                    <span>تسجيل الخروج</span>

                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </form>

        </div>
    </section>
@endsection
