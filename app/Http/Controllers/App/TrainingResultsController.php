<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Models\TrainingResult;
use Illuminate\Http\JsonResponse;

class TrainingResultsController extends BaseController
{
    /**
     * 학습 기록 소요시간 수정
     * PUT|PATCH | app/training-result/{trainingResult}/timer | app.training-result.timer.update
     * @param TrainingResult $trainingResult
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTimer(TrainingResult $trainingResult): JsonResponse
    {
        request()->validate([
            'timer' => 'required|strict_integer'
        ]);

        $timer = request('timer');

        $trainingResult->update(['timer' => $timer]);


        return $this->sendResponse(['timer' => $timer], '성공');
    }
}
