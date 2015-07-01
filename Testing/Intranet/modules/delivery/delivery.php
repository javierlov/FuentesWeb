<?
$valores = array (
	array("Alimentari", "Diagonal Norte 892", "4325-4488", "http://www.alimentari.com/"),
	array("AlmaZen", "Diagonal Norte 871", "Delivery: 4393-0003 (11:30 A 15:30 hs)", "http://almazenresto.blogspot.com/"),
	array("Anahid Panadería", "Hipólito Irigoyen 901 (esq. Tacuarí)", "4331-8400", ""),
	array("Arcángel", "Bartolomé Mitre 943", "4322-6996/0853  ó  4322-0388/0172", "http://www.arcangel.platosdeldia.com"),
	array("Burger King", "", "4322-5794", "http://www.burgerking.com.ar/"),
	array("Capiteles", "Suipacha 130", "4325-4618 / 0889", "http://www.platosdeldia.com.ar/modules.php?name=PDD&func=menucompleto&restid=119"),
	array("Caris", "Pte. Perón 970", "4326-4267/ 4322-5782", ""),
	array("Caserísimo", "Suipacha 201, esq. Perón", "4327-0710", "http://www.platosdeldia.com.ar/modules.php?name=PDD&func=menucompleto&restid=271"),
	array("Como en Casa", "Rivadavia 1161", "4382-1654 / 4383-4975", ""),        
	array("Cuatro Cardinal", "Cerrito 28", "5007-8105", "http://www.cuatrocardinal.com"),        
	array("Delivery Gourmet", "Esmeralda 282", "4328-4990", "http://www.delivery-gourmet.com/"),
	array("Doña Clota", "Sarmiento 985", "4328-7172", ""),
	array("El Noble Repulgue", "", "0-810-333-4444", "http://www.elnoblerepulgue.com.ar/"),
	array("Ensaladas Porteñas", "", "5252-1100", "http://nuevo.ensaladasportenas.com.ar/"),
	array("Havanna", "", "4328-9333", "http://www.havanna.com.ar/"),
	array("La Continental", "Av. de Mayo 1389", "4374-1444", "http://www.lacontinental.com"),
	array("La Fábrica (sándwiches)", "", "0-800-222-8333", "http://www.lafabricalunch.com.ar"),
	array("Latino Sandwich", "Tacuarí 185", "4331-0859/4342-2809", ""),	
	array("La Tropilla", "Pte. Perón 928", "4393-2710", ""),	
	array("Los Garcia", "Sarmiento 1272", "5032- 2050", "http://www.losgarcia.com.ar"),
	array("Mc Donalds", "", "0810-666-1212", "http://www.mcdonalds.com.ar/"),
	array("Milano", "Bartolomé Mitre 745", "4322-9002", ""),
	array("Panetone", "Bartolomé Mitre 747", "4322-2786", ""),
	array("Poker", "Carlos Pellegrini 111", "4326-8620 / 4328-3848", ""),
	array("Sindicato", "Bartolomé Mitre 970", "4331-3958", ""),
	array("Strobels", "Pasaje Carabelas 261", "4328-1865/1275", "http://www.strobels.com.ar/"),
	array("Woki Sushi", "Suipacha176", "4328-9345", ""),
	array("Zapi", "Rivadavia 893", "4343-1001", "")
);
?>
<script>
	showTitle(true, 'DELIVERY');
</script>
<link href="/modules/delivery/css/style_delivery.css" rel="stylesheet" type="text/css">
<div id="divGeneral">
	<p align="center">
		<a href="http://www.platosdeldia.com.ar/modules.php?name=PDD" target="_blank">
			<img border="0" alt="Ver Platos del día" border="0" src="/modules/delivery/images/Platos_del_dia_punto_com.gif">
		</a>
	</p>
	<p>&nbsp;</p>
	<table id="tableDelivery">
		<tr id="trDelivery">
			<td id="tdDelivery">Nombre</td>
			<td id="tdDelivery">Dirección</td>
			<td id="tdDelivery">Teléfono</td>
		</tr>
		<tr>
			<td colspan="3" height="3px"></td>
		</tr>
<?
foreach($valores as $arr) {
	$onClick = "";
	$style = "";
	if ($arr[3] != "") {
		$js = "window.open('".$arr[3]."', '_blank')";
		$onClick = 'onClick="'.$js.'"';
		$style = "cursor: hand;";
	}
?>
		<tr bgcolor="#807F84" class="FondoOnMouseOver" style="color:#fff" <?= $onClick?>>
			<td id="tdItems" style="<?= $style?>"><?= $arr[0]?></td>
			<td id="tdItems" style="<?= $style?>"><?= $arr[1]?></td>
			<td id="tdItems" style="<?= $style?>"><?= $arr[2]?></td>
		</tr>
<?
}
?>
	</table>
</div>