<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Services\TrainingHistoryService;
use Inertia\Inertia;
use App\Models\Student;

class TrainingHistoryController extends BaseController
{
    public function __construct()
    {
    }

    public function index(string $date = null)
    {
        $view = 'training-history/Index';

        /** @var Student */
        $user = auth()->user();

        $trainingHistoryService = new TrainingHistoryService($user, $date);

        $achievementOver4Weeks = $trainingHistoryService->getAchievementOver4Weeks();
        $trainingResultsByCurriculum = $trainingHistoryService->getTrainingResultsByCurriculum();
        $trainingResultsByDate = $trainingHistoryService->getTrainingResultsByDate();

        return Inertia::render($view, [
            'start_date' => $trainingHistoryService->startDate->format('Y-m-d'),
            'end_date' => $trainingHistoryService->endDate->format('Y-m-d'),
            'achievement_over_4weeks' => $achievementOver4Weeks,
            'training_results_by_curriculum' => $trainingResultsByCurriculum,
            'training_results_by_date' => $trainingResultsByDate
        ]);
    }
}
