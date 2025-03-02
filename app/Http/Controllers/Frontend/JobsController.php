<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\JobNotificationEmail;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
     //This method will show jobs page 
    public function index(Request $request){
        $categories = Category::where('status',1)->get();
        $jobTypes = JobType::where('status',1)->get();

        $jobs = Job::where('status',1);
 
        //Search using keyword 
        if (!empty($request->keyword)) {
            // $jobs  = $jobs->orwhere('title','LIKE','%'.$request->keyword.'%');
            // $jobs = $jobs->orwhere('keywords','LIKE','%'.$request->keyword.'%');
            $jobs = $jobs->where(function($query) use($request){
                $query->orwhere('title','LIKE',"%$request->keyword%");
                $query->orwhere('keywords','LIKE',"%$request->keyword%");
            });
        }

        // Search using location
        if(!empty($request->location)){
            $jobs = $jobs->where('location','LIKE',"%$request->location%");
        }

        // Search using Category
        if(!empty($request->category)){
            $jobs = $jobs->where('category_id',$request->category);
        }

        // Search using Job Type
        $jobTypeArray = [];
        if(!empty($request->job_type)){
            // 1,2,3;
            $jobTypeArray = explode(',',$request->job_type);
            $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
        }

        // Search using experience
        if(!empty($request->experience)){
            $jobs = $jobs->where('experience',$request->experience);
        }


         $jobs = $jobs->with(['jobType','category']);

         if($request->sort=='0'){
            $jobs = $jobs->orderBy('created_at','ASC');
         }else{
            $jobs = $jobs->orderBy('created_at','DESC');
         }

        $jobs =  $jobs->paginate(9);

        return view('frontend.jobs',[
            'categories' =>$categories,
            'jobTypes' =>$jobTypes,
            'jobs'    =>$jobs,
            'jobTypeArray' =>$jobTypeArray,
        ]);
       
    }


    // This method will show job detail page
    public function detail($id){
    
        // $job = Job::findOrfail($id);
        $job = Job::where([
                            'id' => $id,
                            'status' => 1
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
        return view('frontend.job-detail',[
                    'job' => $job,
                    'saveJobCount' => $saveJobCount,
                    'applications' =>$applications]);
    }


    public function applyJob(Request $req){
        
        $id = $req->id;

        $job = Job::where([
                            'id' => $id,
                            'status' => 1,
                        ])->first();

        // If job not found in db 
        if($job == null){
            session()->flash('error','Job does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist',
            ]);
        }

        // you can not apply on your own job
        $employer_id = $job->user_id;

        if(Auth::user()->id == $employer_id){
            session()->flash('error','you can not apply on your own job');
            return response()->json([
                'status' => false,
                'message' => 'you can not apply on your own job',
            ]);
        }

        // You can not apply on a job twise
        $jobApplicationCount = JobApplication::where([
                                'job_id' => $id,
                                'user_id' => Auth::user()->id,
                            ])->count();                 

        if($jobApplicationCount > 0){
            session()->flash('error','you already applied on this job');
            return response()->json([
                'status' => false,
                'message' => 'you already applied on  this job',
            ]);

        }                    

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        // Send Notification Email to Employer
        $employer = User::where('id',$employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];
        
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
        
        session()->flash('success','You have successfully applied.');
        return response()->json([
            'status' => true,
            'message' => 'You have successfully applied.',
        ]);
        
    }

    public function saveJob(Request $req){
        $id = $req->id;

        $job = Job::find($id);

         // If job not found in db 
        if($job == null){
            session()->flash('error','Job not found.');
            return response()->json([
                'status' => false,
            ]);
        }
       
        //  Check if user already saved the job
        $saveJobCount = SavedJob::where([
                        'user_id' => Auth::user()->id,
                        'job_id' => $id,
                        ])->count();

        if($saveJobCount > 0){
            session()->flash('error','You already saved this job.');
            return response()->json([
                'status' => false,
            ]);
        }

        
        $savedJob = new SavedJob();
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success','You have successfully saved the job.');
        return response()->json([
            'status' => true,
        ]);
    }
}
    

