<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){

        $jobs = Job::orderBy('created_at','DESC')->with('user','applications')->paginate(10);
        return view('admin.jobs.list',['jobs'=>$jobs]);
    }

    public function edit($id){
        $job = Job::where('id',$id)->first();
        $categories = Category::where('status',1)->orderBy('name','ASC')->get();
        $jobTypes = JobType::where('status',1)->orderBy('name','ASC')->get();

        if($job == null){
            abort('404');
        }
        return view('admin.jobs.edit',[
                            'Job' => $job,
                            'categories' => $categories,
                            'jobTypes' => $jobTypes
                        ]);
    }

    public function update(Request $req,$id){
        // return $req->all();
        $validator = Validator::make($req->all(),[
            'title' =>'required|min:5|max:200',
            'category' =>'required',
            'jobType' =>'required',
            'vacancy' =>'required|integer',
            'location' =>'required|max:50',
            'description' =>'required',
            'company_name' =>'required|min:3|max:75',
            'experience' =>'required',
        ]);
        
        if($validator->passes()){

            $job = Job::find($id);
            $job->title = $req->title;
            $job->category_id  = $req->category;
            $job->job_type_id  = $req->jobType;
            $job->vacancy = $req->vacancy;
            $job->salary = $req->salary;
            $job->location = $req->location;
            $job->description = $req->description;
            $job->benefits = $req->benefits;
            $job->responsibility = $req->responsibility;
            $job->qualifications = $req->qualifications;
            $job->keywords = $req->keywords;
            $job->experience = $req->experience;
            $job->company_name = $req->company_name;
            $job->company_location = $req->company_location;
            $job->company_website = $req->company_website;

            $job->status = $req->status;
            $job->isFeatured = (!empty($req->isFeatured)) ? $req->isFeatured : 0;
            $job->save();

            session()->flash('success','Job Update successfully.');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);


        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        
    }

    public function destroy(Request $req){
        // return $req->all();
        $id = $req->id;
        $job = Job::find($id);

        if ($job == null) {
            session()->flash('error','Either job deleted or not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        $job->delete();
        session()->flash('success','Job deleted successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function detail($id){
    
        // $job = Job::findOrfail($id);
        $job = Job::where([
                            'id' => $id,
                            // 'status' => 1
                        ])->with(['jobType','category'])->first();

        if($job == null){
            abort("404");
        }  
        
        $saveJobCount = 0;
        if(Auth::check()){
            $saveJobCount = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id,
                ])->count();
        }

        // fetch Applicants
        $applications  = JobApplication::where('job_id',$id)->with('user')->get();
        return view('admin.jobs.job-detail',[
                    'job' => $job,
                    'saveJobCount' => $saveJobCount,
                    'applications' =>$applications]);
    }
}
