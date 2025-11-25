<?php

namespace App\Services;

use App\Interfaces\AttachmentRepositoryInterface;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttachmentService
{
    /**
     * @var AttachmentRepositoryInterface
     */
    protected $attachmentRepository;

    /**
     * @param AttachmentRepositoryInterface $attachmentRepository
     */
    public function __construct(AttachmentRepositoryInterface $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function uploadAttachment(Ticket $ticket, UploadedFile $file, $uploadedBy = null): Attachment
    {
        if ($uploadedBy instanceof User) {
            $uId = $uploadedBy->id;
        } elseif (is_int($uploadedBy)) {
            $uId = $uploadedBy;
        } else {
            $uId = Auth::id();
        }

        $path = $file->store('attachments', 'public');
        return $this->attachmentRepository->create([
            'ticket_id' => $ticket->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $uId,
        ]);
    }

    /**
     * Delete single attachment: remove file on disk then delete DB record.
     * Returns true on success, false on failure.
     */
    public function deleteAttachment(int $attachmentId): bool
    {
        $attachment = $this->attachmentRepository->find($attachmentId);
        if (! $attachment) {
            return false;
        }

        DB::beginTransaction();
        try {
            $disk = $attachment->disk ?? 'public';
            $path = ltrim($attachment->file_path, '/');

            if ($path && Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }

            $this->attachmentRepository->delete($attachmentId);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('deleteAttachment failed', ['attachment_id' => $attachmentId, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Delete all attachments for a ticket (files + records) in one transaction.
     */
    public function deleteAttachmentsForTicket(Ticket $ticket): bool
    {
        // try to get attachments via repository if available, otherwise via relation
        $attachments = method_exists($this->attachmentRepository, 'getByTicket')
            ? $this->attachmentRepository->getByTicket($ticket->id)
            : ($ticket->relationLoaded('attachments') ? $ticket->attachments : $ticket->attachments()->get());

        if ($attachments->isEmpty()) {
            return true;
        }

        DB::beginTransaction();
        try {
            $disk = 'public';
            $ids = [];
            foreach ($attachments as $att) {
                $disk = $att->disk ?? $disk;
                $path = ltrim($att->file_path ?? $att->path ?? '', '/');
                if ($path && Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
                $ids[] = $att->id;
            }

            foreach ($ids as $id) {
                $this->attachmentRepository->delete($id);
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('deleteAttachmentsForTicket failed', ['ticket_id' => $ticket->id ?? null, 'error' => $e->getMessage()]);
            return false;
        }
    }
}
