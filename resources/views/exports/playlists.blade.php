<table>
    <thead>
    <tr>
        <th>Réponses</th>
        <th>Origine</th>
        <th>Lien</th>
        <th>Difficulté</th>
        <th>Vote +</th>
        <th>Vote 1</th>
        <th>Ajouté le</th>
    </tr>
    </thead>
    <tbody>
    @foreach($playlist->tracks as $track)
        <tr>
            <td>
                <ul>
                @foreach($track->answers as $answer)
                    <li>{{ $answer->type->name }} : {{ $answer->value }}</li>
                @endforeach
                </ul>
            </td>
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
