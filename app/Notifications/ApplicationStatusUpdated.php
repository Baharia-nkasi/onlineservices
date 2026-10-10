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
            'rejected' => __('Application rejected — action required'),
            'processing' => __('Application is being processed'),
            'completed' => __('Application completed'),
            default => __('Application updated'),
        };

        $message = match ($status) {
            'approved' => $this->remarkUpdated && $remark !== ''
                ? $remark
                : __('Your :service application has been approved.', ['service' => $serviceName]),
            'rejected' => $remark !== ''
                ? $remark
                : __('Your :service application was rejected. Please start a new application and submit your documents again.', ['service' => $serviceName]),
            'processing' => __('Your :service application is now being processed.', ['service' => $serviceName]),
            'completed' => __('Your :service application has been completed. Please review your application details for the final update.' , ['service' => $serviceName]),
            default => __('Your :service application has been updated.', ['service' => $serviceName]),
        };

        return [
            'application_id' => $this->application->id,
            'service_name' => $serviceName,
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'remark' => $remark !== '' ? $remark : null,
            'url' => $status === 'rejected'
                ? route('services.index')
                : route('customer.applications.show', $this->application),
        ];
    }
}
