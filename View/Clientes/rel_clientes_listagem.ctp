<div class="relatorio1">

	<table>
		
		<thead>
			<tr>
				<th> Código </th>
				<th> Nome </th>
				<th>Logradouro</th>
				<th>Número</th>
				<th>Complemento</th>
				<th> Bairro </th>
				<th> Cidade </th>
				<th> UF </th>
				<th> Situação </th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($consulta as $cliente) {
				print "<tr>\n";
				print "<td> {$cliente['Cliente']['id']} </td>";
				print "<td> {$cliente['Cliente']['nome']} </td>";
				print "<td> {$cliente['Cliente']['logradouro_nome']} </td>";
				print "<td> {$cliente['Cliente']['logradouro_numero']} </td>";
				print "<td> {$cliente['Cliente']['logradouro_complemento']} </td>";
				print "<td> {$cliente['Cliente']['bairro']} </td>";
				print "<td> {$cliente['Cliente']['cidade']} </td>";
				print "<td> {$cliente['Cliente']['uf']} </td>";
				print "<td> {$opcoes_situacoes[$cliente['Cliente']['situacao']]} </td>";
				print "</tr>\n";
			}
			?>
		</tbody>

	</table>

</div>