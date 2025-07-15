@extends('core::components.layouts.master')
@section('content')
 <main class="col main-content">
        <div class="dashboard-header">
          <div>
            <div class="welcome">مرحبًا بك في نظام إدارة الصيدليات</div>
            <div class="subtitle">لوحة تحكم متطورة لإدارة الصيادلة، الموردين، الأدوية والطلبات بكل سهولة واحترافية.</div>
          </div>
          <img src="https://cdn.pixabay.com/photo/2017/01/31/13/14/medicine-2028240_1280.png" alt="pharmacy" style="height:70px;max-width:120px;border-radius:12px;box-shadow:0 2px 8px #e0e7ff;">
        </div>
        <div class="row g-4">
          <div class="col-md-6 col-lg-4">
            <a href="pharmacists.html" class="stat-card text-decoration-none">
              <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
              <div class="stat-info">
                <div class="stat-title">عدد الصيادلة</div>
                <div class="stat-value">{{ $pharmacistCount }}</div>
              </div>
              <span class="stat-link">عرض التفاصيل <i class="bi bi-arrow-left-short"></i></span>
            </a>
          </div>
          <div class="col-md-6 col-lg-4">
            <a href="suppliers.html" class="stat-card text-decoration-none">
              <div class="stat-icon"><i class="bi bi-truck"></i></div>
              <div class="stat-info">
                <div class="stat-title">عدد الموردين</div>
                <div class="stat-value">{{ $supplierCount }}</div>
              </div>
              <span class="stat-link">عرض التفاصيل <i class="bi bi-arrow-left-short"></i></span>
            </a>
          </div>
          <div class="col-md-6 col-lg-4">
            <a href="medicines.html" class="stat-card text-decoration-none">
              <div class="stat-icon"><i class="bi bi-capsule"></i></div>
              <div class="stat-info">
                <div class="stat-title">عدد الأدوية</div>
                <div class="stat-value">{{ $medicineCount }}</div>
              </div>
              <span class="stat-link">عرض التفاصيل <i class="bi bi-arrow-left-short"></i></span>
            </a>
          </div>
          <div class="col-md-6 col-lg-6">
            <a href="orders.html" class="stat-card text-decoration-none">
              <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
              <div class="stat-info">
                <div class="stat-title">عدد الطلبات</div>
                <div class="stat-value">23</div>
              </div>
              <span class="stat-link">عرض التفاصيل <i class="bi bi-arrow-left-short"></i></span>
            </a>
          </div>
          <div class="col-md-6 col-lg-6">
            <a href="sales_report.html" class="stat-card text-decoration-none">
              <div class="stat-icon"><i class="bi bi-bar-chart-line"></i></div>
              <div class="stat-info">
                <div class="stat-title">إجمالي المبيعات</div>
                <div class="stat-value">5,050 ريال</div>
              </div>
              <span class="stat-link">عرض التقارير <i class="bi bi-arrow-left-short"></i></span>
            </a>
          </div>
        </div>
      </main>
@endsection
