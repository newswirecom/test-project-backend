<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::query()
            // Only shows jobs that are ready to be worked on
            ->where('scheduled_at', '<=', Carbon::now())
            ->where('assigned_worker_id', null)
            ->where('is_deleted', false)
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        return JobResource::collection($jobs);
    }

    public function show(Request $request, Job $job)
    {
        return $job;
    }
}
