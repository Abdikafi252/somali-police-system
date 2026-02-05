<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PoliceCase;
use App\Models\User;

class CaseAssignedNotification extends Notification
{
    use Queueable;

    protected $case;
    protected $assigner;

    /**
     * Create a new notification instance.
     */
    public function __construct(PoliceCase $case, User $assigner)
    {
        $this->case = $case;
        $this->assigner = $assigner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Kiis Cusub ayaa laguugu soo xilsaaray.',
            'description' => "Baare {$notifiable->name}, waxaa laguu soo xilsaaray kiiska sumadiisu tahay {$this->case->case_number}. Fadlan bilow baarista sida ugu dhaqsaha badan.",
            'case_id' => $this->case->id,
            'case_number' => $this->case->case_number,
            'assigned_by' => $this->assigner->name,
            'type' => 'case_assignment',
            'action_url' => route('cases.show', $this->case->id),
        ];
    }
}
