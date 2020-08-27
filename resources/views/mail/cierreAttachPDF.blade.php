<!DOCTYPE html>
<html>
<head>
	<title>Cierre de caja</title>
	<style type="text/css">

		* {
			font-family: sans-serif;
			font-size: 11px;
		}

		body {
			padding: 0px;
			margin: 0px;
		}

		.container {
			padding: 20px;
		}

		.header {
			padding: 30px 20px;
			font-size: 30px;
			font-weight: bold;
			background-color: #003b7c;
			color: white;
		}

		h3.title {
			background-color: #28a745;
			color: white;
			padding: 20px;
		}

		th, td {
		  	border-bottom: 1px solid #ddd;
		  	padding: 5px;
  			text-align: left;
		}
		tr:nth-child(even) {background-color: #f2f2f2;}

	</style>
</head>
<body>

<div class="header">
	YPF
</div>

<div class="container">
	<h1>Cierre de Turno</h1>


<h5>{{ $d['cierre']['usuario'] }} |
{{ Carbon\Carbon::parse($d['fechas']['fhdc'])->format('d-m-Y h:i:s A') }} - 
{{ Carbon\Carbon::parse($d['fechas']['fhhc'])->format('d-m-Y h:i:s A') }} </h5>

<h3 class="title">Resumen</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>Tipo</th>
			<th style="text-align: right">Facturado</th>
			<th style="text-align: right">Rendido</th>
			<th style="text-align: right">Dif</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Contado</td>
			<td style="text-align: right">{{ $d['contado'] }}</td>
			<td style="text-align: right">{{ $d['arqueo_contado'] }}</td>
			<td style="text-align: right">{{ number_format($d['contado'] - $d['arqueo_contado'],2) }}</td>
		</tr>
		<tr>
			<td>CtaCte</td>
			<td style="text-align: right">{{ $d['ctacte'] }}</td>
			<td style="text-align: right">-</td>
			<td style="text-align: right">-</td>
		</tr>
		<tr>
			<td>Tarjeta</td>
			<td style="text-align: right">{{ $d['tarjeta'] }}</td>
			<td style="text-align: right">{{ $d['arqueo_tarjeta'] }}</td>
			<td style="text-align: right">{{ number_format($d['tarjeta'] - $d['arqueo_tarjeta'],2) }}</td>
		</tr>
	</tbody>
</table>

<h3 class="title">Facturado</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>Articulo</th>
			<th>Denominacion</th>
			<th style="text-align: right">Cantidad</th>
		</tr>
	</thead>
	<tbody>
		@foreach($d['facturado'] as $fac)
		<tr>
			<td>{{ $fac->articulo }}</td>
			<td>{{ $fac->denominacion }}</td>
			<td style="text-align: right">{{ $fac->cantidad }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<h3 class="title">Incidencias</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>Articulo</th>
			<th>Denominacion</th>
			<th style="text-align: right">Cantidad</th>
		</tr>
	</thead>
	<tbody>
		@foreach($d['incidencias'] as $i)
		<tr>
			<td>{{ $i->articulo }}</td>
			<td>{{ $i->denominacion }}</td>
			<td style="text-align: right">{{ $i->cantidad }}</td>
		</tr>
		@endforeach
	</tbody>
</table>


<h3 class="title">Control Aforadores Agro</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>Articulo</th>
			<th>Nombre</th>
			<th style="text-align: right">Inicial</th>
			<th style="text-align: right">Final</th>
		</tr>
	</thead>
	<tbody>
		@foreach($d['aforadores'] as $a)
		<tr>
			<td>{{ $a->articulo }}</td>
			<td>{{ $a->nombre }}</td>
			<td style="text-align: right">{{ $a->inicial }}</td>
			<td style="text-align: right">{{ $a->final }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<h3 class="title">Control</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>Articulo</th>
			<th>Denominacion</th>
			<th style="text-align: right">Incial</th>
			<th style="text-align: right">Yer</th>
			<th style="text-align: right">Reposicion</th>
			<th style="text-align: right">Final</th>
			<th style="text-align: right">Venta</th>
		</tr>
	</thead>
	<tbody>
		@foreach($d['control'] as $c)
		<tr>
			<td>{{ $c->codigo }}</td>
			<td>{{ $c->denominacion }}</td>
			<td style="text-align: right">{{ $c->inicial }}</td>
			<td style="text-align: right">{{ $c->yer }}</td>
			<td style="text-align: right">{{ $c->reposicion }}</td>
			<td style="text-align: right">{{ $c->final }}</td>
			<td style="text-align: right">{{ number_format($c->inicial - $c->final - $c->yer + $c->reposicion,2) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>


<h3 class="title">Comprobantes</h3>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<thead>
		<tr>
			<th>NROCOMPROBANTE</th>
			<th>TIPOCOMP</th>
			<th>NOMBRE</th>
			<th style="text-align: right">IMPTARJ</th>
			<th style="text-align: right">TOTALCOM</th>
		</tr>
	</thead>
	<tbody>
		@foreach($d['comprobantes'] as $c)
		<tr>
			<td>{{ $c->nrocomprobante }}</td>
			<td>{{ $c->tipocomp }}</td>
			<td>{{ $c->nombre }}</td>
			<td style="text-align: right">{{ $c->imptarj }}</td>
			<td style="text-align: right">{{ $c->totalcom }}</td>
		</tr>
		@endforeach
	</tbody>
</table>




<br>

</div>



</body>
</html>