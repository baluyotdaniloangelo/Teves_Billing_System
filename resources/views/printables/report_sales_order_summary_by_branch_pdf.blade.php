<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Order Summary by Branch</title>
    {!! $printable_styles !!}
</head><body>
@foreach($branch_documents as $document)
    <div style="page-break-before: {{ $loop->first ? 'auto' : 'always' }};">{!! $document !!}</div>
@endforeach
</body></html>
