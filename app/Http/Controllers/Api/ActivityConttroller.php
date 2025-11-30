<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Http\Resources\ActivityResource;
use App\Http\Requests\ListActivityRequest;

class ActivityConttroller extends Controller
{
    public function index(ListActivityRequest $request)
    {
        $data = $request->validated();
        $query = Activity::with('causer');
        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('description', 'like', "%{$q}%");
            });
        }

        if (!empty($data['event'])) {
            $query->where('event', $data['event']);
        }
        $perPage = $data['size'] ?? 10;
        $activities = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return ActivityResource::collection($activities);
    }
}
