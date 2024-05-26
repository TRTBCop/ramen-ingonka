<?php

namespace App\Traits;

use App\Models\Academy;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

trait ActivityLogTrait
{
    use LogsActivity;
    use CausesActivity;

    protected string $logName = '';
    protected string $description = '';
    protected int $isShow = 0;
    protected ?int $academyId = null;

    public function tapActivity(Activity $activity): void
    {
        // 추가필드
        $activity->is_show = $this->isShow;
        $activity->academy_id = $this->academyId;
    }

    public function setActivitylogOptions($arrParam): self
    {
        if (isset($arrParam['academy_id']) && $arrParam['academy_id']) {
            $this->academyId = $arrParam['academy_id'];
        }
        if (isset($arrParam['log_name']) && $arrParam['log_name']) {
            $this->logName = $arrParam['log_name'];
        }

        if (isset($arrParam['description']) && $arrParam['description']) {
            $this->description = $arrParam['description'];
        }
        if (isset($arrParam['is_show']) && $arrParam['is_show']) {
            $this->isShow = $arrParam['is_show'];
        }
        return $this;
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()            // 변경된 항목만 저장
            ->dontSubmitEmptyLogs();    // 변경되지 않은 항목 스킵
        // Chain fluent methods for configuration options

        $routeName = Route::currentRouteName();

        if ($this instanceof Academy) { // 학원 모델인경우 학원 번호를 넣어줌
            $this->isShow = 1;
            $this->academyId = $this->id;
        }

        // 지정된 로그네임이 있다면
        if ($this->logName) {
            $logOptions->useLogName($this->logName);
        } elseif ($routeName) {
            $logOptions->useLogName($routeName);
        }

        if ($this->description) {
            $logOptions->setDescriptionForEvent(function () {
                return $this->description;
            });
        }

        return $logOptions;
    }
}
