<html>
<?php
date_default_timezone_set('Europe/Brussels');
$graden_breedte = "51.21175";
$graden_lengte = "3.4345";
if ($_POST['verzend']):
	$breedte =($_POST['breedte']=="ZB")?"-":"+";
	$lengte = ($_POST['lengte']=="WL")?"-":"+";
	$graden_breedte = $_POST['graden_breedte'];
	$graden_lengte = $_POST['graden_lengte'];
endif;
?>
<head>
<style type="text/css">
<!--
.GroeneTekst {
	font-weight: bold;
	color: #00CC33;
}
.RodeTekst {
	font-weight: bold;
	color: #FF0000;
}
.maandkop {
	font-weight: bold;
	color: #ffffff;
	background-color:#154565;
-->
</style>
</head>
<body>
<div id="coordinaten">
<form id="form1" name="form1" method="post" action="">
	<table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>N-breedte</td>
        <td><input name="breedte" type="radio" value="NB" <?php if($breedte!="-"):print "checked";endif;?>></td>
        <td>&nbsp;</td>
        <td rowspan="2"><input type="text" name="graden_breedte" value="<?php print $graden_breedte;?>"/></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Z-breedte</td>
        <td><input name= "breedte" type="radio" value="ZB" <?php if($breedte=="-"):print "checked";endif;?>></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>W-lengte</td>
        <td><input name= "lengte" type="radio" value="WL" <?php if($lengte=="-"):print "checked";endif;?>></td>
        <td>&nbsp;</td>
        <td rowspan="2"><input type="text" name="graden_lengte" value="<?php print $graden_lengte;?>"/></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>O-lengte</td>
        <td><input name= "lengte" type="radio" value="OL" <?php if($lengte!="-"):print "checked";endif;?>></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" name="verzend" value="verzenden" /></td>
        <td>&nbsp;</td>
      </tr>
    </table>
	</form>
</div>
<div id="kaart">
<iframe width="600" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
src="http://maps.google.com/maps?q=<?php print $breedte.$graden_breedte;?>,<?php print $lengte.$graden_lengte;?>&amp;hl=nl&amp;ie=UTF8&amp;ll=<?php print $breedte.$graden_breedte;?>,<?php print $lengte.$graden_lengte;?>&amp;spn=0.344114,0.825348&amp;z=10&amp;output=embed">
</iframe></div>
<br>
<?php 

//$eerste_les = mktime(0,0,0,1,1,2009);
//$laatste_les = mktime(0,0,0,12,16,2009);
$weken = date("W",$laatste_les) - date("W",$eerste_les);// dit klopt zolang je niet naar een ander jaar springt
//$maldegem = date_sun_info ( $laatste_les , 51.12  , 3.26 );// dat doet er hier niet meer toe
//$sevilla = date_sun_info ( $laatste_les , 37.37  , -5.98 );//dat doet er hier niet meer toe, het wordt berekened hier wat onder

for($i=1;$i<=366;$i++):
	$datum = mktime(0,0,0,1,$i,2024);
	$gegevens = date_sun_info ($datum,$graden_breedte,$graden_lengte);
	$zon_info[$datum]= $gegevens;
	endfor;
$laatste_dag_vorig_jaar = mktime(0,0,0,12,31,2023);
$oudewaarde = date_sun_info($laatste_dag_vorig_jaar,$graden_breedte,$graden_lengte);
$oudemaand = 12;
$daglengte_gisteren = $oudewaarde['sunset']-$oudewaarde['sunrise'];

//var_dump($zon_info);
?>

<?php
foreach($zon_info as $sleutel => $waarde):
	$kleur = (date("m",$sleutel)%2==0)?"#CEF6F5":"#CEE3F6";
	if (date("D",$sleutel)== "Sat") $kleur="#Ffcc00#";
	if (date("D",$sleutel)== "Sun") $kleur="#Ff9900#";
	if (date("m",$sleutel)!=$oudemaand):?>
		<table border="0" cellpadding = "2" cellspacing = "1">
		<tr class="maandkop">
			<td width="70"><?php print date("F",$sleutel)?></td>
			<td width="80">datum</td>
		  <td width="1" bgcolor = "white"></td>
			<td width="80">zon op</td>
			<td width="80">verschil</td>
			<td width="1" bgcolor = "white"></td>
			<td width="80">zon onder</td>
			<td width="80">verschil</td>
			<td width="1" bgcolor = "white"></td>
			<td width="80">daglengte</td>
			<td width="80">verschil</td>
			<td width="1" bgcolor = "white"></td>
			<td width="100">hoogste punt</td>
		</tr>
	<?php endif?>
		<tr bgcolor = "<?php print $kleur;?>">
			<td><?php print date("D",$sleutel);?></td>
			<td><?php print date("d/m/Y",$sleutel);?></td>
			<td bgcolor = "white"></td>
			<td><?php print date("H:i:s",$waarde['sunrise']);?></td>
			<?php $groen_of_rood = date("H:i:s",$oudewaarde['sunrise'])>date("H:i:s",$waarde['sunrise'])?"groenetekst":"rodetekst";?>
			<td class="<?php print $groen_of_rood?>"><?php print date("H:i:s",$oudewaarde['sunrise'])>date("H:i:s",$waarde['sunrise'])?
			"+ ".date("i:s",$oudewaarde['sunrise']-$waarde['sunrise']):
			"- ".date("i:s",$waarde['sunrise']-$oudewaarde['sunrise']);?></td>
			<td bgcolor = "white"></td>
			<td><?php print date("H:i:s",$waarde['sunset']);?></td>
			<?php $groen_of_rood = date("H:i:s",$oudewaarde['sunset'])>date("H:i:s",$waarde['sunset'])?"rodetekst":"groenetekst"?>
			<td class="<?php print $groen_of_rood?>"><?php print date("H:i:s",$oudewaarde['sunset'])>date("H:i:s",$waarde['sunset'])?
			"- ".date("i:s",$oudewaarde['sunset']-$waarde['sunset']):
			"+ ".date("i:s",$waarde['sunset']-$oudewaarde['sunset']);?></td>
			<td bgcolor = "white"></td>
			<td><?php print date("H:i:s",$waarde['sunset']-$waarde['sunrise']);?></td>
			<?php $groen_of_rood = date("H:i:s",$daglengte_gisteren)<date("H:i:s",$waarde['sunset']-$waarde['sunrise'])?"groenetekst":"rodetekst"?>
			<td class="<?php print $groen_of_rood?>"><?php print date("H:i:s",$daglengte_gisteren)<date("H:i:s",$waarde['sunset']-$waarde['sunrise'])?
			"+ ".date("i:s",($waarde['sunset']-$waarde['sunrise'])-$daglengte_gisteren):
			"- ".date("i:s",$daglengte_gisteren-($waarde['sunset']-$waarde['sunrise']))?></td>
			<td bgcolor = "white"></td>
			<td><?php print date("H:i:s",$waarde['transit']);?></td>
		</tr>
		<?php
		$oudewaarde = $waarde;
		$oudemaand = date("m",$sleutel);
		$daglengte_gisteren = $waarde['sunset']-$waarde['sunrise'];
		if ($oudemaand != date("m",$sleutel+60*60*24)):?>
			</table><br>
		<?php endif?>
<?php endforeach;?>
	
<!-- 
<table border="1">
<tr>
<td><center><b>2 0 0 9 - MALDEGEM</b></center></td>
<td>ZON OP</td>
<td>ZON ONDER</td>
<td>daglengte</td>
</tr>
<?php for ($i=1; $i<=365;$i++):?>
<tr>
<?php $zon = date_sun_info (mktime(0,0,0,1,$i,2017),51.2167,3.45);?> 
<td bgcolor="yellow"><?php print date("d/m/Y",$zon[sunset]);?></td>
<td><?php print date("H:i:s",$zon['sunrise']);?></td>
<td><?php print date("H:i:s",$zon['sunset']);?></td>
<td><?php print date("H:i:s",$zon['sunset']-$zon['sunrise']);?></td>
</tr>
<?php endfor;?>
</table>
-->

</body>
</html>