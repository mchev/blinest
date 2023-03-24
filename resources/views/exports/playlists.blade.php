<table>
    <thead>
    <tr>
        @foreach($answer_types as $type)
            <th>{{ __($type->name) }}</th>
        @endforeach
        <th>Origine</th>
        <th>Lien</th>
        <th>Difficulté</th>
        <th>Vote +</th>
        <th>Vote 1</th>
        <th>Ajouté le</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tracks as $track)
        <tr>
            @foreach($answer_types as $type)
                <td>
                    <ul>
                        @foreach($track->answers()->where('answer_type_id', $type->id)->get() as $answer)
                            <li>{{ $answer->value }}</li>
                        @endforeach
                    </ul>
                </td>
            @endforeach
            <td>{{ $track->provider }}</td>
            <td>{{ $track->provider_url }}</td>
            <td>{{ $track->dificulty }}</td>
            <td>{{ $track->totalUpvotes }}</td>
            <td>{{ $track->totalDownvotes }}</td>
            <td>{{ $track->created_at->format('d/m/y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
