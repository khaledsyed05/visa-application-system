Dear {{ $application->applicant_name }},

The status of your visa application (ID: {{ $application->id }}) has been updated.

New Status: {{ ucfirst($application->status) }}

@if($application->admin_notes)
Additional Notes: {{ $application->admin_notes }}
@endif

If you have any questions, please don't hesitate to contact us.

Thank you for using our visa application service.

Best regards,
Budget Wings.