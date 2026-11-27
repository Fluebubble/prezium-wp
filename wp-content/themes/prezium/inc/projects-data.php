<?php
/** Demo-Projekte der Startseite und die dafür verwendeten Produkte. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function prezium_demo_project_brands() {
	return array(
		'Lumen Atelier' => 'Lumen Atelier entwirft Leuchten aus Messing, Opalglas und mundgeblasenem Kristall. Demo-Marke für die Referenzprojekte.',
	);
}

function prezium_demo_projects() {
	return array(
		'villa-mit-doppelraum' => array(
			'title'   => 'Villa mit Doppelraum',
			'image'   => '08-projekt1.webp',
			'excerpt' => 'Parkett · Fenster · Licht',
			'facts'   => array( 'Typ' => 'Privatvilla', 'Ort' => 'Rheinland (Demo)', 'Fläche' => 'ca. 420 m²', 'Bereiche' => 'Parkett, Fenster, Licht' ),
			'content' => "Eine Villa, die den Raum in die Höhe öffnet: Der zweigeschossige Wohnbereich verbindet Eingang, Wohnen und Galerie zu einer großen Geste.\n\nBreite Eichendielen in Naturöl legen sich als ruhiger Teppich durch alle Ebenen. Raumhohe Holz-Alu-Fenster holen das Tageslicht tief ins Haus, am Abend übernimmt eine skulpturale Pendelleuchte die Bühne.\n\nDas Material ist bewusst zurückhaltend gewählt: Eiche, Bronze, Glas. So entsteht ein Haus, das mit den Jahren schöner wird.\n\nDies ist ein Demo-Text.",
		),
		'penthouse-mit-stadtblick' => array(
			'title'   => 'Penthouse mit Stadtblick',
			'image'   => '09-projekt2.webp',
			'excerpt' => 'Parkett · Möbel',
			'facts'   => array( 'Typ' => 'Penthouse', 'Ort' => 'Düsseldorf (Demo)', 'Fläche' => 'ca. 260 m²', 'Bereiche' => 'Parkett, Möbel' ),
			'content' => "Über den Dächern der Stadt wird die Wohnung zum ruhigen Gegenpol. Ein Fischgrät-Parkett in geräucherter Eiche zieht sich durch alle Räume und rahmt den Blick auf die Skyline.\n\nDie Möblierung folgt einer klaren Linie: ein tiefes Sofa, zwei Sessel und ein Couchtisch aus Naturstein, alles in warmen, gedeckten Tönen. Jedes Stück ist so platziert, dass der Raum atmen kann.\n\nDies ist ein Demo-Text.",
		),
		'haus-im-morgennebel' => array(
			'title'   => 'Haus im Morgennebel',
			'image'   => '10-projekt3.webp',
			'excerpt' => 'Terrassendielen · Fenster',
			'facts'   => array( 'Typ' => 'Einfamilienhaus', 'Ort' => 'Eifel (Demo)', 'Fläche' => 'ca. 180 m² plus Terrasse', 'Bereiche' => 'Terrassendielen, Fenster' ),
			'content' => "Ein Haus am Waldrand, das sich zur Landschaft öffnet. Die große Terrasse aus thermisch behandelter Esche und grauem Bambus verlängert den Wohnraum nach draußen und altert in Würde.\n\nEine Hebeschiebetür mit schmalen Rahmen macht aus Wohnzimmer und Terrasse einen einzigen Raum. Der Blick geht ungebrochen in den Morgennebel.\n\nDies ist ein Demo-Text.",
		),
		'boutique-hotel-lobby' => array(
			'title'   => 'Boutique-Hotel Lobby',
			'image'   => '11-projekt4.webp',
			'excerpt' => 'Türen · Licht · Möbel',
			'facts'   => array( 'Typ' => 'Hotel', 'Ort' => 'Köln (Demo)', 'Fläche' => 'Lobby ca. 140 m²', 'Bereiche' => 'Türen, Licht, Möbel' ),
			'content' => "Die Lobby eines kleinen Hotels, das Gästen den Atem nimmt, bevor sie ein Wort gesagt haben. Eine hohe Flügeltür mit Bronzeakzent bildet den Auftritt, ein Kristall-Kronleuchter wirft sein Licht auf Marmor und Messing.\n\nLounge-Sessel und ein massiver Empfangstresen setzen die Handschrift des Hauses fort: wenige Materialien, perfekt verarbeitet.\n\nDies ist ein Demo-Text.",
		),
	);
}

/** [slug, projekt, Titel, Bereich, Marke, Material, Maße, Oberfläche, Beschreibung] */
function prezium_demo_project_products() {
	return array(
		array( 'eiche-landhausdiele-natur', 'villa-mit-doppelraum', 'Eiche Landhausdiele Natur', 'Parkett', 'Ali Parquet', 'Eiche massiv', '2200 × 190 × 15 mm', 'Naturöl', 'Breite Landhausdiele mit lebendiger Maserung für große, ruhige Flächen.' ),
		array( 'holz-alu-fenster-alba', 'villa-mit-doppelraum', 'Holz-Alu-Fenster Alba', 'Fenster', 'Ravaioli Falegnami', 'Eiche, Aluminium', 'Maße nach Aufmaß', 'Bronze eloxiert', 'Raumhohes Holz-Alu-Fenster mit schlanken Profilen und hoher Dichtigkeit.' ),
		array( 'pendelleuchte-corona', 'villa-mit-doppelraum', 'Pendelleuchte Corona', 'Licht', 'Lumen Atelier', 'Messing, Opalglas', 'Ø 90 cm', 'Gebürstetes Messing', 'Skulpturale Pendelleuchte für Räume mit großer Deckenhöhe.' ),
		array( 'stehleuchte-linea', 'villa-mit-doppelraum', 'Stehleuchte Linea', 'Licht', 'Lumen Atelier', 'Messing', 'H 180 cm', 'Schwarz patiniert', 'Schlanke Stehleuchte mit blendfreiem, warmem Licht.' ),
		array( 'fischgraet-raeuchereiche', 'penthouse-mit-stadtblick', 'Fischgrät Räuchereiche', 'Parkett', 'Aeterno Parquet', 'Eiche geräuchert', '600 × 120 × 14 mm', 'Hartwachsöl', 'Klassisches Fischgrätmuster in geräucherter Eiche, handverlegt.' ),
		array( 'sofa-orsa', 'penthouse-mit-stadtblick', 'Sofa Orsa', 'Möbel', 'Aeterno Parquet', 'Leinen, Buche', 'B 260 × T 105 cm', 'Naturleinen', 'Tiefes Sofa mit weichen Linien und massivem Holzsockel.' ),
		array( 'sessel-lume', 'penthouse-mit-stadtblick', 'Sessel Lume', 'Möbel', 'Aeterno Parquet', 'Bouclé, Eiche', 'B 80 × T 85 cm', 'Bouclé Sand', 'Runder Lounge-Sessel mit hohem Sitzkomfort.' ),
		array( 'couchtisch-pietra', 'penthouse-mit-stadtblick', 'Couchtisch Pietra', 'Möbel', 'Aeterno Parquet', 'Naturstein', 'Ø 90 × H 35 cm', 'Geschliffen', 'Couchtisch aus einem Stück Naturstein, jedes Exemplar ein Unikat.' ),
		array( 'terrassendiele-thermoesche', 'haus-im-morgennebel', 'Terrassendiele Thermoesche', 'Terrassendielen', 'Ali Parquet', 'Esche thermisch behandelt', '2400 × 140 × 26 mm', 'Gebürstet', 'Wetterfeste Terrassendiele mit warmem, dunklem Farbton.' ),
		array( 'terrassendiele-bambus-grau', 'haus-im-morgennebel', 'Terrassendiele Bambus Grau', 'Terrassendielen', 'Ali Parquet', 'Bambus verdichtet', '1850 × 137 × 20 mm', 'Grau, geriffelt', 'Besonders harte Bambusdiele für stark genutzte Terrassen.' ),
		array( 'hebeschiebetuer-nebbia', 'haus-im-morgennebel', 'Hebeschiebetür Nebbia', 'Fenster', 'Ravaioli Falegnami', 'Holz-Alu', 'bis 4500 × 2600 mm', 'Anthrazit', 'Große Hebeschiebetür mit schmalen Rahmen für fließende Übergänge.' ),
		array( 'fluegeltuer-aurum', 'boutique-hotel-lobby', 'Flügeltür Aurum', 'Türen', 'Cocif Porte', 'Nussbaum, Bronze', '1400 × 2800 mm', 'Lackiert, Bronzeakzent', 'Zweiflügelige Eingangstür für repräsentative Lobbys.' ),
		array( 'kronleuchter-cascata', 'boutique-hotel-lobby', 'Kronleuchter Cascata', 'Licht', 'Lumen Atelier', 'Kristall, Messing', 'Ø 120 cm', 'Messing poliert', 'Kaskade aus mundgeblasenem Kristall für hohe Lobbys.' ),
		array( 'wandleuchte-arco', 'boutique-hotel-lobby', 'Wandleuchte Arco', 'Licht', 'Lumen Atelier', 'Messing, Opalglas', 'B 40 × H 60 cm', 'Gebürstetes Messing', 'Wandleuchte mit weichem, indirektem Licht.' ),
		array( 'lounge-sessel-salone', 'boutique-hotel-lobby', 'Lounge-Sessel Salone', 'Möbel', 'Aeterno Parquet', 'Samt, Nussbaum', 'B 75 × T 80 cm', 'Samt Smaragd', 'Lounge-Sessel mit hoher Rückenlehne für die Hotellobby.' ),
		array( 'empfangstresen-atrium', 'boutique-hotel-lobby', 'Empfangstresen Atrium', 'Möbel', 'Aeterno Parquet', 'Nussbaum, Marmor', 'B 320 × T 80 cm', 'Geölt, Marmorplatte', 'Massiver Empfangstresen als Herzstück der Lobby.' ),
	);
}
