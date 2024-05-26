<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Http\Controllers\BaseController;
use App\Models\Payment;
use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends BaseController
{
    public function __construct(
        public string $name = '학원',
    ) {
        $this->middleware(['permission:academy']);
    }

    /**
     * 대시보드
     *
     * GET|HEAD | admin | admin.dashboard
     */
    public function __invoke(): Response
    {
        // 이용중
        $paidUsers = Student::where('status', StudentStatusEnum::IN_USE)->count();
        //무료 이용자수
        $freeUsers = Student::where('status', StudentStatusEnum::FREE)->count();
        // 총 학생수
        $allUsers = Student::count();
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        //당월 결제금액 (취소, 환불금액 제외)
        $currentMonthPayAmount = Payment::where('status', 1)
            ->whereBetween('approved_at', [$startDate, $endDate])
            ->sum('amount');

        return Inertia::render('Dashboard', [
            'data' => [
                'paid_user' => $paidUsers,
                'free_user' => $freeUsers,
                'all_user' => $allUsers,
                'current_month_amount' => number_format($currentMonthPayAmount),
            ],
            'page' => [
                'active' => 'admin',
                'title' => '대시보드',
                'breadcrumbs' => []
            ]
        ]);
    }
}
