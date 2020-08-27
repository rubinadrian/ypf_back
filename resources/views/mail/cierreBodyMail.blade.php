<!DOCTYPE html>
<html>
<head>
	<title>Cierre de caja</title>
	<style type="text/css">

	</style>
</head>
<body>

<div class="header">
	YPF
</div>

<h5>{{ $d['cierre']['usuario'] }} |
{{ Carbon\Carbon::parse($d['fechas']['fhdc'])->format('d-m-Y h:i:s A') }} - 
{{ Carbon\Carbon::parse($d['fechas']['fhhc'])->format('d-m-Y h:i:s A') }} </h5>



</body>
</html>