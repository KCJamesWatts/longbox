<table>
    <tbody>
        @foreach ($publishers as $publisher)
            <tr>
                <td>{{ $publisher->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>