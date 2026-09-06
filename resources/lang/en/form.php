<?php

return [
    // Admin + guest shared
    'email' => 'Email',
    'password' => 'Password',
    'title' => 'Title',
    'content' => 'Content',
    'name' => 'Name',
    'phone' => 'Phone',
    'message' => 'Message',
    'submit' => 'Submit',
    'date' => 'Date',

    // Guest contact form
    'send' => 'Send message',
    'intro' => 'Write to us and we will get back to you shortly. For a same-day booking, please call.',
    'sent' => 'Message sent. We will reply as soon as we can.',

    // Contact form spam guard: see App\Support\Captcha.
    'captcha_question' => 'What is :left plus :right?',
    'captcha_hint' => 'Answer in digits.',
    'captcha_failed' => 'That is not the right answer to the security question. Here is a new one.',
    'website' => 'Website',
    'errors' => 'The message was not sent:',
    'number' => [
        1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
        6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
    ],
];
