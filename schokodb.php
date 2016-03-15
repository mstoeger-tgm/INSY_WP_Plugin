<?php
/*
Plugin Name: SchokoDB Plugin
Author: Michael Stoeger
Version: 1.0
Description: Plugin fuer INSY Aufgabe 'Datenbankzugriffe aus Worpress heraus'
*/

/**
 * Funktion zur Anzeige von Schnaeppchen
 * Shortcode: 'schnaeppchen'
 */
function show_schnaeppchen() {
        global $wpdb; //Datenbankverbindung holen
        ob_start(); //echo Aufrufe puffern
	//Schnaeppchen per SQL abrufen
        $res = $wpdb->get_results("(SELECT bezeichnung, gewicht, 'Standardsortiment' AS Art FROM Standardsortiment INNER JOIN Produkt ON Standardsortiment.nummer=Produkt.nummer WHERE preis<3)UNION(SELECT bezeichnung, gewicht, 'Kunstwerk' AS Art FROM Kunstwerk INNER JOIN Produkt ON Kunstwerk.nummer=Produkt.nummer WHERE schaetzwert<2000)");
	echo "<table border='1'><tr><th>Bezeichnung</th><th>Gewicht</th><th>Art</th></tr>";
        foreach ($res as $r) { //Fuer jede Zeile
		echo '<tr>';
		echo '<td>';
                echo $r->bezeichnung;
		echo '</td>';
                echo '<td>';
                echo $r->gewicht;
                echo '</td>';
                echo '<td>';
                echo $r->Art;
                echo '</td>';
		echo '</tr>';
        }
	echo '</table>';
        return ob_get_clean(); //Gepufferte echo Aufrufe zurueckgeben
}
/**
 * Zeigt eine Dropdownliste an, in welcher Kunstschauen ausgewaehlt werden koennen
 * Wurde eine Kunstschau ausgewaehlt zeigt diese Funktion die ausgestellten Werke und den erreichten Platz
 * Shortcode: 'kunstschauen'
 */
function kunstschauen(){
	global $wpdb;
	ob_start();
	//Dropdownliste erstellen
	$res = $wpdb->get_results("SELECT name from Kunstschau");
	echo "<form method='get'><select name='kunstschau'>";
	foreach ($res as $r){
		echo '<option>';
		echo $r->name;
		echo '</option>';
	}
	echo "</select><input type='submit' value='Anzeigen'/></form>";
	//Nur wenn in Form schon etwas ausgewaehlt wurde
        if(isset($_GET['kunstschau'])){ //'kunstschau' ueber GET Methode
        	$input = str_replace("+"," ",$_GET['kunstschau']);
		//SQL Query ueberpruefen
                $tmp = $wpdb->prepare("SELECT Produkt.bezeichnung,zeigt.platz FROM Kunstwerk INNER JOIN zeigt ON Kunstwerk.nummer=zeigt.kunstwerknummer INNER JOIN Produkt ON Kunstwerk.nummer=Produkt.nummer WHERE name=%s",$input);
		//Ueberpruefte SQL Query ausfuehren
		$res = $wpdb->get_results($tmp);
		echo "<table border='1'><tr><th>Bezeichnung</th><th>Platz</th></tr>";
		foreach($res as $r){
			echo '<tr>';
			echo '<td>';
			echo $r->bezeichnung;
			echo '</td>';
			echo '<td>';
			echo $r->platz;
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	return ob_get_clean();
}
//Shortcodes registrieren
add_shortcode('schnaeppchen','show_schnaeppchen');
add_shortcode('kunstschauen','kunstschauen');
