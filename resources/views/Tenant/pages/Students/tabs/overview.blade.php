@php
  $present = (int)($attendanceSummary['present'] ?? $attendanceSummary['Present'] ?? 0);
  $absent  = (int)($attendanceSummary['absent']  ?? $attendanceSummary['Absent']  ?? 0);
  $leave   = (int)($attendanceSummary['leave']   ?? $attendanceSummary['Leave']   ?? 0);
@endphp

<div class="row g-3">
  <div class="col-lg-4">
    <div class="card p-3">
      <h6 class="mb-3">Attendance Summary</h6>
      <canvas id="attendanceDonut"></canvas>
      <div class="row text-center mt-3 small">
        <div class="col">Present<br><strong>{{ $present }}</strong></div>
        <div class="col">Absent<br><strong>{{ $absent }}</strong></div>
        <div class="col">Leave<br><strong>{{ $leave }}</strong></div>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card p-3">
      <h6 class="mb-3">Attendance Trend</h6>
      <canvas id="attendanceTrend"></canvas>
      <div class="text-muted small mt-2">Sample trend (replace with real monthly data if needed)</div>
    </div>
  </div>
  
</div>

<script>
// Donut
new Chart(document.getElementById('attendanceDonut'), {
  type: 'doughnut',
  data: {
    labels: ['Present','Absent','Leave'],
    datasets:[{ data:[{{ $present }}, {{ $absent }}, {{ $leave }}] }]
  },
  options: { plugins:{ legend:{ position:'bottom' } }, cutout:'60%' }
});

// Simple static trend placeholder
new Chart(document.getElementById('attendanceTrend'), {
  type: 'line',
  data: { labels:['Jan','Feb','Mar','Apr','May','Jun'],
          datasets:[{ label:'% Present', data:[92,88,95,90,93,91], tension:.3 }] },
  options: { scales:{ y:{ min:0, max:100, ticks:{ callback:v=>v+'%' } } } }
});
</script>
