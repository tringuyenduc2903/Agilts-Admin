<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected Customer $customer;
    protected string $password;
    protected User $admin;

    /**
     * Create a new message instance.
     *
     * @param Customer $customer
     * @param string $password
     * @param User $admin
     */
    public function __construct(Customer $customer, string $password, User $admin)
    {
        $this->customer = $customer;
        $this->password = $password;
        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Customer account successfully created'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'notifications::email',
            with: [
                'level' => '',
                'introLines' => [
                    trans('Account successfully created by Staff :name at :time.', [
                        'name' => $this->admin->name,
                        'time' => Carbon::now()->isoFormat(config('backpack.ui.default_datetime_format'))
                    ]),
                    trans('Please review the information and access your account for the first time:'),
                    trans('Name: :name', ['name' => $this->customer->name]),
                    trans('Email: :email', ['email' => $this->customer->email]),
                    trans('Phone number: :phone_number', ['phone_number' => $this->customer->phone_number]),
                    trans('Birthday: :birthday', ['birthday' => $this->customer->birthday]),
                    trans('Gender: :gender', ['gender' => $this->customer->gender_preview]),
                    trans('Password: :password', ['password' => $this->password]),
                ],
                'outroLines' => [
                    trans('The password is randomly generated and can only be viewed by you via email.'),
                    trans('For security reasons, please change your password after a successful login!'),
                ],
            ],
        );
    }
}
