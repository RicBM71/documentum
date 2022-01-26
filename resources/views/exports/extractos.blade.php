<table>
    <thead>
    <tr>
        <th>Fecha</th>
        <th>M</th>
        <th>Concepto</th>
        <th>Importe</th>
    </tr>
    </thead>
    <tbody>
    @foreach($extractos as $item)
        <tr>
            <td>{{ getFecha($item->fecha) }}</td>
            <td>{{$item->dh}}</td>
            <td>{{ $item->concepto }}</td>
            <td>{{ $item->importe }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
