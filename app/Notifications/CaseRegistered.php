<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Crime;

class CaseRegistered extends Notification
{
    use Queueable;

    protected $crime;
    protected $caseNumber;

    /**
     * Create a new notification instance.
     */
    public function __construct(Crime $crime, $caseNumber)
    {
        $this->crime = $crime;
        $this->caseNumber = $caseNumber;
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
            'title' => 'Kiis Cusub La Diiwangeliyay',
            'message' => "Kiis cusub ayaa la diiwangeliyay: {$this->caseNumber} - {$this->crime->crime_type}",
            'crime_id' => $this->crime->id,
            'case_number' => $this->caseNumber,
            'crime_type' => $this->crime->crime_type,
            'location' => $this->crime->location,
            'url' => route('crimes.show', $this->crime->id),
        ];
    }
}
