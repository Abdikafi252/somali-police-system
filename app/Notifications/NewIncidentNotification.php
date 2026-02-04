<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PoliceCase;
use App\Models\User;

class NewIncidentNotification extends Notification
{
    use Queueable;

    protected $case;
    protected $reporter;

    public function __construct(PoliceCase $case, User $reporter)
    {
        $this->case = $case;
        $this->reporter = $reporter;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Dhacdo Cusub ayaa la diiwaangeliyay.',
            'description' => "Sarkaalka {$this->reporter->name} ayaa diiwaangeliyay kiis cusub: {$this->case->case_number} ({$this->case->crime->crime_type}).",
            'case_id' => $this->case->id,
            'case_number' => $this->case->case_number,
            'reporter_name' => $this->reporter->name,
            'type' => 'new_incident',
            'action_url' => route('cases.show', $this->case->id),
        ];
    }
}
