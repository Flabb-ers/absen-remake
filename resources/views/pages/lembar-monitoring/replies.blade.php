@foreach ($replies->sortBy('sent_at') as $reply)
    <tr>
        <td>{{ $reply->sender->nama }}  
            <span>({{ getRoleFromType($reply->sender_type) }})</span>
        </td>

        <td>{{ $reply->receiver->nama }}  
            <span>({{ getRoleFromType($reply->receiver_type) }})</span>
        </td>

        <td>{{ $reply->message }}</td>

        <td>{{ \Carbon\Carbon::parse($reply->sent_at)->format('Y-m-d') }}</td>
    </tr>

    @if ($reply->replies->isNotEmpty())
        @include('pages.lembar-monitoring.replies', ['replies' => $reply->replies])
    @endif
@endforeach
