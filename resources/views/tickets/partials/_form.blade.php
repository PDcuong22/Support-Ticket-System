<form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <input type="hidden" name="user_id" value="{{ $ticket->user_id }}">

    @include('tickets.partials._fields')

    @include('tickets.partials._attachments')

    @include('tickets.partials._actions')
</form>