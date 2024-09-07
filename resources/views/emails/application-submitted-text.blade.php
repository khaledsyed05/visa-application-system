Dear {{ $application->applicant_name }},

Your visa application has been successfully submitted. Here are the details:

Application ID: {{ $application->id }}
Visa Type: {{ $application->visaType->name }}
Destination: {{ $application->destination->name }}
Submission Date: {{ $application->created_at->format('F j, Y') }}

We will review your application and update you on its status. You can check your application status using your Application ID.

If you have any questions, please don't hesitate to contact us.

Thank you for using our visa application service.

Best regards,
Budget Wings.