<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class StatusUpdatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $template;

    public function __construct($user)
    {
        $this->user = $user;
        $this->template = EmailTemplate::where('name', 'delivery_success')->first();
    }

    /**
     * Get the message envelope.
     */
    
     public function envelope(): Envelope
      {
        $subject = $this->replaceVariables($this->template->subject);
        
        return new Envelope(
            subject: $subject,
            from: new Address('no-reply@mailtrap.io', 'SongDis Support'),
            replyTo: [
                new Address('no-reply@mailtrap.io', 'SongDis Support')
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = $this->replaceVariables($this->template->content);
        
        return new Content(
            htmlString: $content,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Replace variables in the email content or subject.
     */
    protected function replaceVariables($text)
    {
        $replacements = [
            '{{primary_artist}}' => $this->user->primary_artist,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $text
        );
    }
}
