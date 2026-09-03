<?php
namespace App\Http\Controllers;
use App\Models\AiModelExperiment;
use App\Services\AI\AiEvaluationService;
use Illuminate\Http\Request;
class AdminAiEvaluationController extends Controller
{
 public function index(){return view('admin.ai.evaluation',['experiments'=>AiModelExperiment::latest()->get(),'models'=>config('ai.evaluation_models',[]),'provider'=>config('ai.provider')]);}
 public function run(Request $request,AiEvaluationService $evaluation){$result=$evaluation->run();return back()->with($result['status']==='completed'?'success':'error',$result['status']==='completed'?'ارزیابی مدل‌ها اجرا شد.':'برای ارزیابی، Provider، مدل‌ها و حداقل یک محصول لازم است.');}
}