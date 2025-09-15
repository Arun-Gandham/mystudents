@extends('tenant.layouts.layout1')

@section('title', 'View Student')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Student Profile</h2>

    <!-- Profile Summary Card -->
    <div class="card mb-4 shadow-sm p-3">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                @if($student->photo)
                    <img src="{{ asset('storage/'.$student->photo) }}" 
                         class="rounded-circle img-thumbnail" 
                         width="150" height="150" alt="Student Photo">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                         style="width:150px; height:150px; font-size:2rem;">
                        <i class="bi bi-person"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="mb-1"><b>Admission No:</b> {{ $student->admission_no }}</p>
                        <p class="mb-1"><b>DOB:</b> {{ $student->dob ?? '-' }}</p>
                        <p class="mb-1"><b>Gender:</b> {{ $student->gender ?? '-' }}</p>
                        <p class="mb-1"><b>Phone:</b> {{ $student->phone ?? '-' }}</p>
                        <p class="mb-1"><b>Email:</b> {{ $student->email ?? '-' }}</p>
                        <p class="mb-1"><b>Grade:</b> {{ optional($student->enrollments->first()->grade)->name ?? '-' }}</p>
                        <p class="mb-1"><b>Section:</b> {{ optional($student->enrollments->first()->section)->name ?? '-' }}</p>
                    </div>
                    <div>
                        <a href="{{ tenant_route('tenant.students.edit',['student' => $student->id]) }}" 
                           class="btn btn-primary">
                           <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="studentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                    data-bs-target="#details" type="button" role="tab">Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="guardians-tab" data-bs-toggle="tab" 
                    data-bs-target="#guardians" type="button" role="tab">Guardians</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" 
                    data-bs-target="#addresses" type="button" role="tab">Addresses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" 
                    data-bs-target="#documents" type="button" role="tab">Documents</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-3 border border-top-0" id="studentTabsContent">
        
        <!-- Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <h5>Enrollment</h5>
            @forelse($student->enrollments as $enroll)
                <p>
                    Grade: {{ $enroll->grade->name ?? '-' }} |
                    Section: {{ $enroll->section->name ?? '-' }} |
                    Joined On: {{ $enroll->joined_on }}
                </p>
            @empty
                <p class="text-muted">No enrollment records available.</p>
            @endforelse

            <h5 class="mt-3">General Info</h5>
            <p><b>Aadhaar:</b> {{ $student->aadhaar_no ?? '-' }}</p>
            <p><b>Religion:</b> {{ $student->religion ?? '-' }}</p>
            <p><b>Caste:</b> {{ $student->caste ?? '-' }}</p>
            <p><b>Category:</b> {{ $student->category ?? '-' }}</p>
            <p><b>Blood Group:</b> {{ $student->blood_group ?? '-' }}</p>
        </div>

        <!-- Guardians Tab -->
        <div class="tab-pane fade" id="guardians" role="tabpanel">
            <h5>Guardians</h5>
            @forelse($student->guardians as $g)
                <div class="mb-2 p-2 border rounded">
                    <b>{{ $g->full_name }}</b> ({{ $g->relation }})
                    @if($g->is_primary) <span class="badge bg-primary">Primary</span> @endif
                    <div>Phone: {{ $g->phone_e164 ?? '-' }}</div>
                    <div>Email: {{ $g->email ?? '-' }}</div>
                    <div>Address: {{ $g->address ?? '-' }}</div>
                </div>
            @empty
                <p class="text-muted">No guardians added yet.</p>
            @endforelse
        </div>

        <!-- Addresses Tab -->
        <div class="tab-pane fade" id="addresses" role="tabpanel">
            <h5>Addresses</h5>
            @forelse($student->addresses as $a)
                <div class="mb-2 p-2 border rounded">
                    {{ $a->address_line1 }}, {{ $a->city }}, {{ $a->state }} - {{ $a->pincode }}
                    <br><span class="badge bg-secondary">{{ ucfirst($a->address_type) }}</span>
                </div>
            @empty
                <p class="text-muted">No addresses available.</p>
            @endforelse
        </div>

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            <h5>Documents</h5>
            @forelse($student->documents as $d)
                <div class="mb-2 p-2 border rounded">
                    {{ ucfirst(str_replace('_',' ', $d->doc_type)) }}:
                    <a href="{{ asset('storage/'.$d->file_path) }}" target="_blank">View</a>
                </div>
            @empty
                <p class="text-muted">No documents uploaded.</p>
            @endforelse
        </div>

    </div>

    <!-- Bottom buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ tenant_route('tenant.students.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
