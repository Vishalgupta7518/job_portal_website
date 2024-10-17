@extends('frontend.layouts.main')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit a Job</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('frontend.layouts.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4 ">
                    <form action="" method="post" id="editJobForm" name="editJobForm">
                        @csrf
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Edit Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" value="{{ $Job->title }}" id="title" name="title" class="form-control @error('title')is-invalid @enderror">
                                    <p></p>
                                       
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-select ">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ $Job->category_id==$category->id ?'selected' :''  }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                            
                                        @endif
                                    </select>
                                    <p></p>
                                       
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                    <select value="{{ $Job->jobType  }}" class="form-select" id="jobType" name="jobType">
                                        <option value="">Select a Job Nature</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType )
                                                <option {{ $Job->job_type_id==$jobType->id ?'selected' :'' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                            @endforeach
                                            
                                        @endif
                                    </select>
                                    <p></p>
                                       
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input value="{{ $Job->vacancy  }}" type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                       
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Salary</label>
                                    <input value="{{ $Job->salary  }}" type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location<span class="req">*</span></label>
                                    <input value="{{ $Job->location  }}" type="text" placeholder="location" id="location" name="location" class="form-control">
                                    <p></p>
                                    
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Description">{{ $Job->description  }}</textarea>
                                <p></p>                     
                                   
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Benefits</label>
                                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $Job->benefits  }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Responsibility</label>
                                <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility">{{ $Job->responsibility  }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Qualifications</label>
                                <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications">{{ $Job->qualifications  }}</textarea>
                            </div>
                            
                            

                            <div class="mb-4">
                                <label for="" class="mb-2">Keywords</label>
                                <input value="{{ $Job->keywords  }}" type="text" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-select ">
                                    <option value="">Select Experience</option>
                                    <option value="1" {{ $Job->experience==1 ?'selected' : '' }}>1 Years</option>
                                    <option value="2" {{ $Job->experience==2 ?'selected' : '' }}>2 Years</option>
                                    <option value="3" {{ $Job->experience==3 ?'selected' : '' }}>3 Years</option>
                                    <option value="4" {{ $Job->experience==4 ?'selected' : '' }}>4 Years</option>
                                    <option value="5" {{ $Job->experience==5 ?'selected' : '' }}>5 Years</option>
                                    <option value="6" {{ $Job->experience==6 ?'selected' : '' }}>6 Years</option>
                                    <option value="7" {{ $Job->experience==7 ?'selected' : '' }}>7 Years</option>
                                    <option value="8" {{ $Job->experience==8 ?'selected' : '' }}>8 Years</option>
                                    <option value="9" {{ $Job->experience==9 ?'selected' : '' }}>9 Years</option>
                                    <option value="10" {{ $Job->experience==10 ?'selected' : '' }}>10 Years</option>
                                    <option value="10_plus" {{ $Job->experience=='10_plus' ?'selected' : '' }}>10+ Years</option>
                                </select>
                                <p></p>
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Name<span class="req">*</span></label>
                                    <input value="{{ $Job->company_name }}" type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location</label>
                                    <input value="{{ $Job->company_location }}" type="text" placeholder="Location" id="location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input value="{{ $Job->company_website }}" type="text" placeholder="Website" id="website" name="company_website" class="form-control">
                            </div>
                        </div> 
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update Job</button>
                        </div>
                    </form>

                </div>                   
            </div>
            
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    $('#editJobForm').submit(function(e){
        e.preventDefault(); 
        $('button[type="submit"]').prop('disabled',true);      
        $.ajax({
            url:"{{ route('account.updateJob',$Job->id) }}",
            type:'put',
            data:$('#editJobForm').serializeArray(),
            dataType:'JSON',
            success:function(response){
                $('button[type="submit"]').prop('disabled',false);      

                if (response.status == true) {
                    $('#title').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#jobType').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#vacancy').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#location').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#description').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#company_name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    $('#experience').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                    window.location.href = '{{ route("account.myJobs") }}';
                }else{
                    var errors = response.errors;
                    if (errors.title) {
                        $('#title').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.title);                   
                    }else{
                        $('#title').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.category) {
                        $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.category);                   
                    }else{
                        $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.jobType) {
                        $('#jobType').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.jobType);                   
                    }else{
                        $('#jobType').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.vacancy) {
                        $('#vacancy').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.vacancy);                   
                    }else{
                        $('#vacancy').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.location) {
                        $('#location').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.location);                   
                    }else{
                        $('#location').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.description) {
                        $('#description').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.description);                   
                    }else{
                        $('#description').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.company_name) {
                        $('#company_name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.company_name);                   
                    }else{
                        $('#company_name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if (errors.experience) {
                        $('#experience').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.experience);                   
                    }else{
                        $('#experience').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                    }
                }
                
            }
        });
    });
</script>
@endsection