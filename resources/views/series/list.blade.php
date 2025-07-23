<table>
    <tbody>
        @foreach ($series as $thisSeries)
            <tr>
                <td>{{ $thisSeries->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>