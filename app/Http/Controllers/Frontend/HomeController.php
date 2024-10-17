<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   // This is method will show our Index page
   public function index(){
      $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();

      $newCategories = Category::where('status',1)->orderBy('name','ASC')->get();

      $featureJobs = Job::where('status',1)
                           ->orderBy('created_at','DESC')
                           ->with('jobType')
                           ->where('isFeatured',1)->take(6)->get();
      
      $letestJobs = Job::where('status',1)
                        ->orderBy('created_at','DESC')
                        ->with('jobType')
                        ->take(6)->get();
                                             
      return view('frontend.index',[
         'categories' =>$categories,
         'featureJobs' =>$featureJobs,
         'letestJobs' =>$letestJobs,
         'newCategories' =>$newCategories,
      ]);
   }

   
}
