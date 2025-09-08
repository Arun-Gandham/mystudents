<table class="table table-bordered">
    <thead>
        <tr><th>Grade</th><th>Marks Range</th><th>Remark</th></tr>
    </thead>
    <tbody>
        @foreach($exam->grades as $g)
        <tr>
            <td><span class="badge bg-primary">{{ $g->grade }}</span></td>
            <td>{{ $g->min_mark }} – {{ $g->max_mark }}</td>
            <td>{{ $g->remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>