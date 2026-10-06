<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $status,
        public ?string $remark = null,
        public bool $remarkUpdated = false,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function databaseType(object $notifiable): string
    {
        return 'application-status-updated';
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->status;
        $serviceName = $this->application->service?->name ?? __('Online Service');
        $remark = trim((string) $this->remark);

        $title = match ($status) {
            'approved' => $this->remarkUpdated
                ? __('New message about your application')
                : __('Application approved'),
            'rejected' => __('Application rejected'),
            'processing' => __('Application is being processed'),
            default => __('Application updated'),
        };

        $message = match ($status) {
            'approved' => $this->remarkUpdated && $remark !== ''
                ? $remark
                : __('Your :service application has been approved.', ['service' => $serviceName]),
            'rejected' => __('Your :service application has been rejected.', ['service' => $serviceName]),
            'processing' => __('Your :service application is now being processed.', ['service' => $serviceName]),
            default => __('Your :service application has been updated.', ['service' => $serviceName]),
        };

        return [
            'application_id' => $this->application->id,
            'service_name' => $serviceName,
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'remark' => $remark !== '' ? $remark : null,
            'url' => route('customer.applications.show', $this->application),
        ];
    }
}
