<?php

namespace Apiato\Core\Abstracts\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class Mail extends Mailable
{
    use Queueable, SerializesModels;
}
