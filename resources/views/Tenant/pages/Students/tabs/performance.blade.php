<div class="card p-3">
  <h6>Average Marks by Subject</h6>
  <canvas id="chartPerformance"></canvas>
  @if(($subjectMarks ?? collect())->isEmpty())
    <div class="text-muted small mt-2">No exam results available.</div>
  @endif
</div>

<script>
const perfLabels = @json(($subjectMarks ?? collect())->keys()->values());
const perfData   = @json(($subjectMarks ?? collect())->values());
new Chart(document.getElementById('chartPerformance'), {
  type: 'bar',
  data: { labels: perfLabels, datasets: [{ label:'Avg Marks', data: perfData }] },
  options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>
