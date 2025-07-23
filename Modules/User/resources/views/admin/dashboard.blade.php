@extends('core::components.layouts.master')

@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1 p-1">مرحبًا بك في نظام إدارة الصيدليات</h2>
                <p>لوحة تحكم متطورة لإدارة الصيادلة، الموردين، الأدوية والطلبات بكل سهولة
                    واحترافية.
                </p>
            </div>
        </div>
    </div>
    @role('المشرف')
        <div class="row row-sm">
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-primary-transparent">
                                <i class="bi bi-person-badge text-primary"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">عدد الصيادلة</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $pharmacistCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-success-transparent">
                                <i class="bi bi-truck text-success" style="transform: scaleX(-1);"></i>
                            </div>
                            <div class="mr-auto">
                                <h5>عدد الموردين</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $supplierCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-danger-transparent">
                                <i class="bi bi-capsule text-danger"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">عدد الأدوية</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $medicineCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-sm-12 col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-warning-transparent">
                                <i class="bi bi-bag-check text-warning"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">عدد الطلبات</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $orderCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-info-transparent">
                                <i class="bi bi-bar-chart-line text-info"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">طلبات جديدة</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">15</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elserole('مورد')
        <div class="row row-sm">
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-danger-transparent">
                                <i class="bi bi-capsule text-danger"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">عدد أدوية مستودعي</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $myMedicineCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-warning-transparent">
                                <i class="bi bi-bag-check text-warning"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">عدد الطلبات</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">{{ $myOrderCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="counter-status d-flex md-mb-0">
                            <div class="counter-icon bg-info-transparent">
                                <i class="bi bi-bar-chart-line text-info"></i>
                            </div>
                            <div class="mr-auto">
                                <h5 class="tx-13">طلبات جديدة</h5>
                                <h2 class="mb-0 tx-22 mb-1 mt-1">15</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endrole
@endsection
