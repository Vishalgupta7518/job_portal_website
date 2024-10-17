@extends('frontend.layouts.main')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Jobs Applied</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.layouts.sidebar')
            </div>
            <div class="col-lg-9">
                @include('frontend.layouts.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <h3 class="fs-4 mb-1">Jobs Applied</h3>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Applied Date</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobApplocations->isNotEmpty())
                                        @foreach ($jobApplocations as $jobApplocation )
                                        <tr class="active">
                                            <td>
                                                <div class="job-name fw-500">{{ $jobApplocation->job->title }}</div>
                                                <div class="info1">{{ $jobApplocation->job->jobType->name }} . {{ $jobApplocation->job->location }}</div>
                                            </td>
                                            <td>{{ date('d M, Y',strtotime($jobApplocation->applied_date)) }}</td>
                                            <td>{{ $jobApplocation->job->applications->count() }} Applications</td>
                                            <td>
                                                @if ($jobApplocation->job->status == 1)
                                                    <div class="job-status text-capitalize">active</div>
                                                @else
                                                    <div class="job-status text-capitalize">Block</div>    
                                                @endif
                                                
                                            </td>
                                            <td>
                                                <div class="action-dots float-end">
                                                    <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('jobDetail',$jobApplocation->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="removeJob({{ $jobApplocation->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr> 
                                        @endforeach 
                                    @else
                                        <tr>
                                            <td class="text-danger text-center" colspan="5">Job Not Found.</td>
                                        </tr>
                                    @endif                                                                          
                                </tbody>
                            </table>
                        </div>
                        <div>
                            {{ $jobApplocations->links('pagination::bootstrap-5') }}
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
    function removeJob(id){
       if(confirm('Are you sure you want to remove?')){
            $.ajax({
                url:'{{ route("account.removeJobs") }}',
                type:'post',
                data:{id:id},
                dataType:'json',
                success:function(response){
                    window.location.href = '{{ route("account.myJobApplications") }}';                    
                }
            });
       }
        
    } 
</script>

@endsection