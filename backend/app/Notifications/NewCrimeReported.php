<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Crime;

class NewCrimeReported extends Notification
{
    use Queueable;

    public $crime;

    /**
     * Create a new notification instance.
     */
    public function __construct(Crime $crime)
    {
        $this->crime = $crime;
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
            'crime_id' => $this->crime->id,
            'case_number' => $this->crime->case_number,
            'crime_type' => $this->crime->crime_type,
            'location' => $this->crime->location,
            'description' => $this->crime->description,
            'reporter_name' => $this->crime->reporter->name ?? 'N/A',
            'reporter_station' => $this->crime->reporter->station->station_name ?? 'Xarunta Dhexe',
            'message' => 'Dambi cusub ayaa la diiwaangeliyay: ' . $this->crime->case_number,
        ];
    }
}
