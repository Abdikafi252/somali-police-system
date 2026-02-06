<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Suspect;

class NewSuspectAdded extends Notification
{
    use Queueable;

    public $suspect;

    /**
     * Create a new notification instance.
     */
    public function __construct(Suspect $suspect)
    {
        $this->suspect = $suspect;
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
            'suspect_id' => $this->suspect->id,
            'name' => $this->suspect->name,
            'age' => $this->suspect->age,
            'photo' => $this->suspect->photo,
            'crime_type' => $this->suspect->crime->crime_type ?? 'N/A',
            'location' => $this->suspect->crime->location ?? 'N/A',
            'case_number' => $this->suspect->crime->case_number ?? 'N/A',
            'status' => $this->suspect->arrest_status,
            'reporter_name' => $this->suspect->crime->reporter->name ?? 'N/A',
            'reporter_station' => $this->suspect->crime->reporter->station->station_name ?? 'Xarunta Dhexe',
            'message' => 'Dambiile cusub ayaa la qabtay: ' . $this->suspect->name,
            'type' => 'suspect_alert' // Custom type for styling
        ];
    }
}
