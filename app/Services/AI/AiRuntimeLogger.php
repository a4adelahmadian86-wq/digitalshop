<?php

namespace App\Services\AI;

use App\Models\AiRuntimeLog;
use Throwable;

class AiRuntimeLogger
{
    public function start(string $operation, ?string $provider=null, ?string $model=null, array $meta=[]): AiRuntimeLog
    {
        return AiRuntimeLog::create(['user_id'=>auth()->id(),'session_id'=>session()->getId(),'operation'=>$operation,'provider'=>$provider,'model'=>$model,'status'=>'running','request_meta'=>$meta]);
    }

    public function finish(AiRuntimeLog $log, string $status='success', array $meta=[], ?int $latencyMs=null, ?int $inputTokens=null, ?int $outputTokens=null, ?float $cost=null): void
    {
        $log->update(['status'=>$status,'latency_ms'=>$latencyMs,'input_tokens'=>$inputTokens,'output_tokens'=>$outputTokens,'cost'=>$cost,'response_meta'=>$meta]);
    }

    public function fail(AiRuntimeLog $log, Throwable $e, ?int $latencyMs=null): void
    {
        $this->finish($log,'failed',['exception'=>get_class($e)],$latencyMs);
        $log->update(['error_message'=>mb_substr($e->getMessage(),0,5000)]);
    }
}
