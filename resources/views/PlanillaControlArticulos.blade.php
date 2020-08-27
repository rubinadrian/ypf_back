<style type="text/css">

	table {
		width: 100%;
	}

	td {
		border: 1px solid #ccc;
		font-size: 10px;
		padding: 0px 5px;
	}

	th {
		font-size: 12px;
		background: #666;
		color: white;
		padding: 0px 5px;
	}

	tr:nth-child(odd) {
	    background-color:#f2f2f2;
	}
	tr:nth-child(even) {
	    background-color:#fbfbfb;
	}

	.numero {
		width: 60px;
	}
</style>


<table>
  <thead>
    <tr>
      <th class="numero">Codigo</th>
      <th>Denominacion</th>
      <th class="numero">Inicial</th>
      <th class="numero">Control</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $articulo)
      <tr>
        <td class="numero">{{ $articulo->codigo }}</td>
        <td>{{ $articulo->denominacion }}</td>
        <td class="numero">{{ $articulo->inicial }}</td>
        <td class="numero"></td>
      </tr>
    @endforeach
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    @foreach($aforadores as $afo)
      <tr>
        <td class="numero">{{ $afo->articulo }}</td>
        <td>{{ $afo->nombre }}</td>
        <td class="numero">{{ $afo->inicial }}</td>
        <td class="numero"></td>
      </tr>
    @endforeach
  </tbody>
</table>