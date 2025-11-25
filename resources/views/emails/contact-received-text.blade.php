New Contact Form Submission
================================

You have received a new message from your website contact form.

Name: {{ $contact->name }}

Email: {{ $contact->email }}

@if($contact->phone)
Phone: {{ $contact->phone }}

@endif
@if($contact->subject)
Subject: {{ $contact->subject }}

@endif
Message:
{{ $contact->message }}

Submitted: {{ $contact->created_at->format('d M Y, h:i A') }}

---
This email was sent from the contact form on your website.
HTR ENGINEERING PTE LTD
105 Sims Avenue #05-11 Chancerlodge Complex, Singapore 387429
