<?php

namespace App\Services;

use App\Interfaces\AttachmentRepositoryInterface;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function uploadAttachment(Ticket $ticket, UploadedFile $file)
    {
        $path = $file->store('attachments', 'public');
        return $this->attachmentRepository->create([
            'ticket_id' => $ticket->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function deleteAttachment(int $attachmentId){
        $attachment = $this->attachmentRepository->find($attachmentId);

        if (!$attachment) {
            return false;
        }

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        return $this->attachmentRepository->delete($attachmentId);
    }

    public function getAttachmentUrl(Attachment $attachment)
    {
        return Storage::url($attachment->file_path);
    }
}
