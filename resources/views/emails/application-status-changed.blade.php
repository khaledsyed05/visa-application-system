The status of your visa application (ID: {{ $application->id }}) has been updated.

New Status: {{ ucfirst($application->status) }}

@if(!empty($adminNotes))
Additional Notes:
@foreach($adminNotes as $note)
- {{ $note }}
@endforeach
@endif

If you have any questions, please don't hesitate to contact us.

Thank you for using our visa application service.

Best regards,
Budget Wings.