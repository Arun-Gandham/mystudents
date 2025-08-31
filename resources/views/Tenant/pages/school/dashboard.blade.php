@extends('superadmin.pages.school.base')

@php $activeTab = 'dashboard'; @endphp

@section('tabcontent')
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white"><strong><i class="bi bi-graph-up-arrow me-1"></i>Attendance – Last 14 Days</strong></div>
      <div class="card-body"><canvas id="chartAttendance" height="120"></canvas></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white"><strong><i class="bi bi-pie-chart me-1"></i>Today</strong></div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col"><div class="h4 mb-0">1,153</div><div class="text-muted small">Present</div></div>
          <div class="col"><div class="h4 mb-0">95</div><div class="text-muted small">Absent</div></div>
        </div>
        <canvas id="chartToday" height="160"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white"><strong><i class="bi bi-journal-check me-1"></i>Upcoming Exams</strong></div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between">
          <div><div class="fw-semibold">Mathematics – Grade 10</div>
          <div class="text-muted small"><i class="bi bi-calendar-event me-1"></i>12 Sep, 09:30 AM</div></div>
          <span class="badge bg-primary-subtle text-primary">Hall A</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <div><div class="fw-semibold">Physics – Grade 12</div>
          <div class="text-muted small"><i class="bi bi-calendar-event me-1"></i>14 Sep, 11:30 AM</div></div>
          <span class="badge bg-primary-subtle text-primary">Hall C</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <div><div class="fw-semibold">English – Grade 8</div>
          <div class="text-muted small"><i class="bi bi-calendar-event me-1"></i>16 Sep, 10:00 AM</div></div>
          <span class="badge bg-primary-subtle text-primary">Hall B</span>
        </li>
      </ul>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white"><strong><i class="bi bi-trophy me-1"></i>Top Performing Classes</strong></div>
      <div class="card-body">
        <div class="d-flex flex-column gap-2">
          <div class="d-flex justify-content-between"><span>Grade 10-A</span><span class="badge bg-success-subtle text-success">96.2%</span></div>
          <div class="d-flex justify-content-between"><span>Grade 9-B</span><span class="badge bg-success-subtle text-success">95.1%</span></div>
          <div class="d-flex justify-content-between"><span>Grade 8-C</span><span class="badge bg-success-subtle text-success">94.8%</span></div>
          <div class="d-flex justify-content-between"><span>Grade 12-A</span><span class="badge bg-success-subtle text-success">94.4%</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- Chart.js (page-scoped) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  // Attendance trend (static demo)
  const ctx1 = document.getElementById('chartAttendance').getContext('2d');
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: ['16 Aug','17','18','19','20','21','22','23','24','25','26','27','28','29'],
      datasets: [
        { label: 'Present', data: [1110,1122,1105,1128,1140,1118,1130,1126,1135,1142,1130,1138,1145,1153], tension:.3 },
        { label: 'Absent',  data: [98,95,100,92,88,96,90,93,85,81,90,86,82,95], tension:.3 }
      ]
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
  });

  // Today pie (static demo)
  const ctx2 = document.getElementById('chartToday').getContext('2d');
  new Chart(ctx2, {
    type: 'doughnut',
    data: { labels:['Present','Absent'], datasets:[{ data:[1153,95] }] },
    options: { cutout:'65%', plugins:{ legend:{ position:'bottom' } } }
  });
</script>
@endpush
