<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;//
use App\Http\Controllers\Frontend\RegisterController;//
use App\Http\Controllers\Frontend\JobsController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\JobController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{jobId}',[JobsController::class,'detail'])->name('jobDetail');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobsController::class,'saveJob'])->name('saveJob');

Route::get('/forgot-password',[RegisterController::class,'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password',[RegisterController::class,'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}',[RegisterController::class,'resetPassword'])->name('account.resetPassword');
Route::put('/process-reset-password',[RegisterController::class,'processResetPassword'])->name('account.processResetPassword');

Route::group(['prefix'=>'admin','middleware'=>'checkRole'],function(){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('/users',[UserController::class,'index'])->name('admin.users');
    Route::get('users/{id}',[UserController::class,'edit'])->name('admin.users.edit');
    Route::put('users/{id}',[UserController::class,'update'])->name('admin.users.update');
    Route::delete('/users',[UserController::class,'destroy'])->name('admin.users.destroy');
    Route::get('/jobs',[JobController::class,'index'])->name('admin.jobs');
    Route::get('jobs/edit/{id}',[JobController::class,'edit'])->name('admin.jobs.edit');
    Route::put('jobs/{id}',[JobController::class,'update'])->name('admin.jobs.update');
    Route::delete('/jobs',[JobController::class,'destroy'])->name('admin.jobs.destroy');
    Route::get('/jobs/detail/{jobId}',[JobController::class,'detail'])->name('admin.jobs.detail');
    Route::get('/job-applications',[JobApplicationController::class,'index'])->name('admin.jobApplications');
    Route::delete('/job-applications',[JobApplicationController::class,'destroy'])->name('admin.jobApplications.destroy');


});    

Route::group(['prefix'=>'account'],function(){
    // Guest Routes
    Route::group(['middleware'=>'guest'],function(){
        Route::get('/register',[RegisterController::class,'registration'])->name('account.registration');
        Route::post('/process-register',[RegisterController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('/login',[RegisterController::class,'login'])->name('account.login');
        Route::post('/authenticate',[RegisterController::class,'authenticate'])->name('account.authenticate');
    });
    
    // Authenticated Routes
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile',[RegisterController::class,'profile'])->name('account.profile');
        Route::get('/logout',[RegisterController::class,'logout'])->name('account.logout');
        Route::put('/updateProfile',[RegisterController::class,'updateProfile'])->name('account.updateProfile');
        Route::put('/updatePassword',[RegisterController::class,'updatePassword'])->name('account.updatePassword');
        Route::post('/update-Profile-Pic',[RegisterController::class,'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-Job',[RegisterController::class,'createJob'])->name('account.createJob');
        Route::post('/save-Job',[RegisterController::class,'saveJob'])->name('account.saveJob');
        Route::get('/my-Jobs',[RegisterController::class,'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}',[RegisterController::class,'editJob'])->name('account.editJob');
        Route::put('/update-job/{jobId}',[RegisterController::class,'updateJob'])->name('account.updateJob');
        Route::delete('/delete-job',[RegisterController::class,'deleteJob'])->name('account.deleteJob');
        Route::get('/my-job-applications',[RegisterController::class,'myJobApplications'])->name('account.myJobApplications');
        Route::post('/remove-job-application',[RegisterController::class,'removeJob'])->name('account.removeJobs');
        Route::get('/saved-jobs',[RegisterController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('/remove-saved-job',[RegisterController::class,'removeSavedJob'])->name('account.removeSavedJob');
        
        
    });
});



