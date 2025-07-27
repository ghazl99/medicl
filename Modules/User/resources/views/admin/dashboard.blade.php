@extends('core::components.layouts.master')
<style>
    .card {
        background: #fff;
        border-radius: 18px !important;
        box-shadow: 0 4px 24px rgba(37, 99, 235, 0.08);
    }

    h2.main-content-title {
        color: #0d6efd;
        /* لون bootstrap primary */
    }

    .dark-theme h2.main-content-title {
        color: #fff;
    }

    .card {
    background: #fff;
    border-radius: 18px !important;
    box-shadow: 0 4px 24px rgba(41, 41, 43, 0.08) !important;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 8px 32px rgb(49 45 90 / 35%) !important;
}

</style>
@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title mb-2 mg-b-1 mg-b-lg-1 p-1">مرحبًا بك في نظام إدارة الصيدليات</h2>
                <p style="color: #5c6a7d">لوحة تحكم متطورة لإدارة الصيادلة، الموردين، الأدوية والطلبات بكل سهولة
                    واحترافية.
                </p>
            </div>
        </div>
    </div>

    @role('المشرف')
        <div class="row row-sm">
            <!-- الصيادلة -->
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-primary-transparent me-3">
                                <i class="bi bi-person-badge text-primary"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد الصيادلة</h5>
                                <h2 class="mb-0 text-primary">{{ $pharmacistCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('pharmacists.index') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- الموردين -->
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-success-transparent me-3">
                                <i class="bi bi-truck text-success" style="transform: scaleX(-1);"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد الموردين</h5>
                                <h2 class="mb-0  text-success">{{ $supplierCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('suppliers.index') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- الأدوية -->
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-danger-transparent me-3">
                                <i class="bi bi-capsule text-danger"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد الأدوية</h5>
                                <h2 class="mb-0  text-danger">{{ $medicineCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('medicines.index') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- الطلبات -->
            <div class="col-sm-12 col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-warning-transparent me-3">
                                <i class="bi bi-bag-check text-warning"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد الطلبات</h5>
                                <h2 class="mb-0 text-warning">{{ $orderCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('orders.index') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @elserole('مورد')
        <div class="row row-sm">
            <!-- أدوية المورد -->
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-danger-transparent me-3">
                                <i class="bi bi-capsule text-danger"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد أدوية مستودعي</h5>
                                <h2 class="mb-0  text-danger">{{ $myMedicineCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('medicines.my') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- طلبات المورد -->
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="counter-icon bg-warning-transparent me-3">
                                <i class="bi bi-bag-check text-warning"></i>
                            </div>
                            <div class="mr-3 d-flex flex-column justify-content-center">
                                <h5 class="mb-1">عدد الطلبات</h5>
                                <h2 class="mb-0 text-warning">{{ $myOrderCount }}</h2>
                            </div>
                        </div>
                        <a href="{{ route('orders.my') }}" class=" mt-3 align-self-end">
                            عرض التفاصيل <i class="bi bi-arrow-left-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endrole
@endsection
