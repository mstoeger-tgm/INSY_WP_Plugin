<?php
/*
Plugin Name: SchokoDB Plugin
Author: Michael Stoeger
Version: 1.0
Description: Plugin fuer INSY Aufgabe 'Datenbankzugriffe aus Worpress heraus'
*/
function show_schnaeppchen() {
        global $wpdb;
        ob_start();
        $res = $wpdb->get_results("(SELECT bezeichnung, gewicht, 'Standardsortiment' AS Art FROM Standardsortiment INNER JOIN Produkt ON Standardsortiment.nummer=Produkt.nummer WHERE preis<3)UNION(SELECT bezeichnung, gewicht, 'Kunstwerk' AS Art FROM Kunstwerk INNER JOIN Produkt ON Kunstwerk.nummer=Produkt.nummer WHERE schaetzwert<2000)");
	echo "<table border='1'><tr><th>Bezeichnung</th><th>Gewicht</th><th>Art</th></tr>";
        foreach ($res as $r) {
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
        return ob_get_clean();
}
add_shortcode('schnaeppchen','show_schnaeppchen');
