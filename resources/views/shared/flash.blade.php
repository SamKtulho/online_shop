@if($message = flash()->get())
    <div class="{{ $message->class() }} p-6">
        {{ $message->message() }}
    </div>
@endif
