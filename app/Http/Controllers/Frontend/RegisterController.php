<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    // This method will show user registration page
    public function registration(){
        return view('frontend.register');
    }

    // This method will save user
    public function processRegistration(Request $req){
        
        $validator = Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:5|same:confirm_password',
            'confirm_password'=>'required',
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->save();

            session()->flash('success','You have registerd successfully.');

            return response()->json([
                'status' => true,
                'errors' =>[],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors(),
            ]);

        }

        // $req->validate([
        //     'name'=>'required',
        //     'email'=>'required|email|unique:users,email',
        //     'password'=>'required|min:5',
        //     'confirm_password'=>'required|same:password',
        // ]);

        // return redirect()->route('account.login')->with('success','You have registerd successfully.');
    }

    // This method will show user login page
    public function login(){
        return view('frontend.login');
    }

    public function authenticate(Request $req){

        $validator  = Validator::make($req->all(),[
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if($validator->passes()){

             if (Auth::attempt(['email'=>$req->email, 'password'=>$req->password])) {
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error','Either Email/Password is incorrect');
            }

        }else{
            return redirect()->route('account.login')->withErrors($validator)->withInput($req->only('email'));
        }

        // $req->validate([
        //     'email' =>'required|email',
        //     'password' =>'required',
        // ]);

        // if (Auth::attempt(['email'=>$req->email, 'password'=>$req->password])) {
        //     return redirect()->route('account.profile');
        // }else{
        //     return redirect()->route('account.login')->with('error','Either Email/Password is incorrect');
        // }
    }

    public function profile(){
        $id = Auth::user()->id;
        // $user = User::where('id',$id)->first();
        $user  = User::find($id);
        //dd($user);
        return view('frontend.account',compact('user'));
    }

    public function updateProfile(Request $req){
        $id = Auth::user()->id;
        $validator = Validator::make($req->all(),[
                'name' =>'required|min:5|max:20',
                'email'=>'required|email|unique:users,email,'.$id.',id',
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name = $req->name;
            $user->email = $req->email;
            $user->designation = $req->designation;
            $user->mobile = $req->mobile;
            $user->save();

            session()->flash('success','Profile update Successfully.');

            return response()->json([
                'status' => true,
                'errors' =>[],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        // $req->validate([
        //     'name' =>'required|min:5|max:20',
        //     'email'=>'required|email|unique:users,email,'.$id.',id',
        // ]);
        // $user = User::find($id);
        // $user->name = $req->name;
        // $user->email = $req->email;
        // $user->designation = $req->designation;
        // $user->mobile = $req->mobile;
        // $user->save();

        // return redirect()->route('account.profile')->with('success','Profile update Successfully.');

    }

    public function updatePassword(Request $req){

        $validator = Validator::make($req->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors'  => $validator->errors(),
            ]);
        }

        if(Hash::check($req->old_password,Auth::user()->password) == false){
            session()->flash('error','Your old Password is incorrect.');
            return response()->json([
                'status' => true,
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($req->new_password);
        $user->save();

        session()->flash('success','Password updated successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $req){
        // dd($req->all());
        $id = Auth::user()->id;
        $validator = Validator::make($req->all(),[
                'image' =>'required|image'
        ]);

        if($validator->passes()){
            
            $image = $req->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('profile_pic/'),$imageName);
            // Create a small thumbnail

            // create new image instance (800 x 600)
                $sourcePath = public_path('profile_pic/'.$imageName); 
                $manager = new ImageManager(Driver::class);
                $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
                $image->cover(150, 150);
                $image->toPng()->save(public_path('profile_pic/thumb/'.$imageName));

            // Delete Old Profile Pic
            File::delete(public_path('profile_pic/thumb/'.Auth::user()->image)); 
            File::delete(public_path('profile_pic/'.Auth::user()->image)); 

            User::where('id',$id)->update(['image'=>$imageName]);

            session()->flash('success','Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' =>[],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // $req->validate([
        //     'image' =>'required|image'
        // ]);
        
        // session()->flash('success','Profile picture updated successfully.');
        // return redirect()->route('account.profile');
    }

    public function createJob(){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('frontend.post-job',[
            'categories'=>$categories,
            'jobTypes'=>$jobTypes
        ]);
    }

    public function saveJob(Request  $req){

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

            $job = new Job();
            $job->title = $req->title;
            $job->category_id  = $req->category;
            $job->job_type_id  = $req->jobType;
            $job->user_id  = Auth::user()->id;
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
            $job->save();

            session()->flash('success','Job added successfully.');

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
        
        // return redirect()->route('account.myJobs');


    }

    public function myJobs(){
        $jobs = Job::orderBy('created_at','DESC')->where('user_id',Auth::user()->id)->with('jobType')->Paginate(10);
        // return $jobs;
        return view('frontend.my-jobs',compact('jobs'));

    }

    public function editJob($id){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $Job = Job::where([
            'user_id' => Auth::user()->id,
            'id'      =>$id
        ])->first();

        if ($Job == null) {
            abort('404');
        }

        return view('frontend.edit',[
            'categories' =>$categories,
            'jobTypes'   =>$jobTypes,
            'Job'        =>$Job,

        ]);
    }

    public function updateJob(Request  $req,$id){
        // echo $id;die;
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
            $job->user_id  = Auth::user()->id;
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

        // return redirect()->route('account.myJobs');

    }

    public function deleteJob(Request $req){
        $job = Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$req->jobId,
        ])->first();

        if($job == null){
            session()->flash('error','Either job deleted or not found.');

            return response()->json([
                'status' => false,
            ]);
       
        }
        
        Job::where('id',$req->jobId)->delete();

        session()->flash('success','Job deleted Successfully.');
        return response()->json([
            'status' => true,
        ]);

    }


    public function myJobApplications(){
        $jobApplocations = JobApplication::where('user_id',Auth::user()->id)
                        ->with(['job','job.jobType','job.applications'])
                        ->orderBy('created_at','DESC')
                        ->paginate(10);

        return view('frontend.job-applied',[
            'jobApplocations' => $jobApplocations,
        ]);
    }

    public function removeJob(Request $req){
        $jobApplocation = JobApplication::where([
                                    'id' => $req->id,
                                    'user_id' => Auth::user()->id,]
                                    )->first();
        if ($jobApplocation == null) {
            session()->flash('error','Job application not found.');
            return response()->json([
                'status' => false,
            ]);
        }
        
        JobApplication::where('id',$req->id)->delete();      
        session()->flash('success','Job application removed successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function savedJobs(){
        $savedJobs = SavedJob::where('user_id',Auth::user()->id)
                        ->with(['job','job.jobType','job.applications'])
                        ->orderBy('created_at','DESC')
                        ->paginate(10);

        return view('frontend.saved-jobs',[
            'savedJobs' => $savedJobs,
        ]);
    }
    
    public function removeSavedJob(Request $req){
        $savedJob = SavedJob::where([
                                    'id' => $req->id,
                                    'user_id' => Auth::user()->id,]
                                    )->first();
        if ($savedJob == null) {
            session()->flash('error','Job not found.');
            return response()->json([
                'status' => false,
            ]);
        }
        
        SavedJob::where('id',$req->id)->delete();      
        session()->flash('success','Job removed successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function forgotPassword(){
        return view('frontend.account.forgot-password');
    }

    public function processForgotPassword(Request $req){
        $validator = Validator::make($req->all(),[
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')
                            ->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$req->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $req->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send Email here
        $user  = User::where('email',$req->email)->first();
        $mailData = [
            'token' => $token,
            'user'  => $user,
            'subject' => 'You have requested to change your password.',
        ];

        Mail::to($req->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success','Reset password email has been sent to your inbox.');

    }

    public function resetPassword($tokenString){
        $token = \DB::table('password_reset_tokens')->where('token',$tokenString)->first();

        if($token == null){
            return redirect()->route('account.forgotPassword')->with('error','Invalid Token.');
        }

        return view('frontend.account.reset-password',['tokenString' => $tokenString]);
    }


    public function processResetPassword(Request $req){

        $token = \DB::table('password_reset_tokens')->where('token',$req->token)->first();

        if($token == null){
            return redirect()->route('account.forgotPassword')->with('error','Invalid Token.');
        }

        $validator = Validator::make($req->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword',$req->token)->withErrors($validator);
        }
        

        User::where('email',$token->email)->update([
            'password' => Hash::make($req->new_password),
        ]);
        return redirect()->route('account.login')->with('success','You have successfully changed your password.');


    }

  
}
