<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Storage;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $role = $user ? strtolower($user->role->name) : '';

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->whenLoaded('status', function () {
                return $this->status ? [
                    'id' => $this->status->id,
                    'name' => $this->status->name,
                ] : null;
            }),
            'priority' => $this->whenLoaded('priority', function () {
                return $this->priority ? [
                    'id' => $this->priority->id,
                    'name' => $this->priority->name,
                ] : null;
            }),
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name]);
            }),
            'labels' => $this->whenLoaded('labels', function () {
                return $this->labels->map(fn($l) => ['id' => $l->id, 'name' => $l->name]);
            }),
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($a) {
                    /** @var \Illuminate\Filesystem\FilesystemAdapter */
                    $disk = Storage::disk('public');
                    $url = $a->file_path ? $disk->url($a->file_path) : null;
                    return [
                        'id' => $a->id,
                        'file_name' => $a->file_name,
                        'mime_type' => $a->mime_type,
                        'size' => $a->file_size,
                        'url' => $url,
                        'created_at' => $a->created_at,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (in_array($role, ['admin', 'support agent'])) {
            $data['user'] = $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null;
            });
            $data['assigned_to'] = $this->whenLoaded('assignedUser', function () {
                return $this->assignedUser ? [
                    'id' => $this->assignedUser->id,
                    'name' => $this->assignedUser->name,
                    'email' => $this->assignedUser->email,
                ] : null;
            });
        }
        return $data;
    }

    public function with(Request $request): array
    {
        if ($this->resource instanceof AbstractPaginator) {
            return [
                'meta' => [
                    'total' => $this->resource->total(),
                    'current_page' => $this->resource->currentPage(),
                    'per_page' => $this->resource->perPage(),
                    'last_page' => $this->resource->lastPage(),
                ],
            ];
        }
        return [];
    }
}
