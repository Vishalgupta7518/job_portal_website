@extends('frontend.layouts.main')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('frontend.layouts.message')
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                {{-- <form action="">
                                    <input type="search" placeholder="search by name and eamil">
                                    <input type="submit" class="btn btn-primary btn-sm" value="search">
                                </form> --}}
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobs->isNotEmpty())
                                        @foreach ($jobs as $job)
                                            <tr class="active">
                                                <td>
                                                    <div class="job-name fw-500">{{ $job->id }}</div>
                                                </td>
                                                <td>
                                                    <div class="job-name fw-500">{{ $job->title }}</div>
                                                    <p>Applicants: {{ $job->applications->count() }}</p>
                                                </td>
                                                <td>
                                                    <div class="job-name fw-500">{{ $job->user->name }}</div>
                                                </td>
                                                <td>
                                                    @if ($job->status == 1)
                                                    <div class="job-name fw-500 text-success">Active</div>
                                                    @else
                                                    <div class="job-name fw-500 text-danger">Block</div>
                                                    @endif                                                    
                                                </td>
                                                <td>
                                                    <div class="job-name fw-500">{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</div>
                                                </td>
                                                <td>
                                                    <div class="action-dots">
                                                        <button  class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('admin.jobs.detail',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> View</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.jobs.edit',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>                             
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                                
                            </table>
                            <div>
                                {{ $jobs->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>                               
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script type="text/javascript">
    function deleteJob(JobId){
         if (confirm('Are you sure you want to delete?')) {
             $.ajax({
                 url:"{{ route('admin.jobs.destroy') }}",
                 type:'delete',
                 data:{id:JobId},
                 dataType:'json',
                 success:function(response){
                         window.location.href = "{{ route('admin.jobs') }}";
                     
                 }
             });
             
         }
     }
 </script>

@endsection
