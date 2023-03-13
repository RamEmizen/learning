<style type="text/css">
	table td, table th{
		border:1px solid black;
	}
</style>
<div class="container">


	<br/>
	{{-- <a href="{{ route('pdfview',['download'=>'pdf']) }}">Download PDF</a> --}}


	<table>
		<tr>
			<th>No</th>
			<th>Title</th>
			<th>Description</th>
		</tr>
		@foreach ($userData as $key => $item)
		<tr>
			<td>{{ ++$key }}</td>
			<td>{{ $item->first_name }}</td>
			<td>{{ $item->last_name }}</td>
		</tr>
		@endforeach
	</table>
</div>