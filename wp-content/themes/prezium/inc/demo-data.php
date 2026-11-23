<?php
/**
 * Demo-Inhalte (alles Testdaten). Marken, Produkte, Texte, Seiten.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function prezium_demo_brands() {
	return array(
		'Cocif Porte'        => 'Cocif Porte fertigt Innentüren in einer norditalienischen Manufaktur. Klare Linien, flächenbündige Zargen und lackierte oder furnierte Oberflächen prägen die Kollektion. Jede Tür entsteht auf Maß und wird in Handarbeit finalisiert.',
		'Ali Parquet'        => 'Ali Parquet steht für Massivholzparkett und Terrassendielen aus nachhaltig bewirtschafteten Wäldern. Die Dielen werden sortiert, getrocknet und mit Naturöl oder Hartwachsöl veredelt.',
		'Ravaioli Falegnami' => 'Ravaioli Falegnami baut Holz- und Holz-Alu-Fenster nach Maß. Die Schreinerei verbindet Tradition mit modernster Dichttechnik und liefert Fenster, Hebeschiebetüren und Sonderleuchten für anspruchsvolle Projekte.',
		'Aeterno Parquet'    => 'Aeterno Parquet kombiniert Parkettkollektionen mit Möbeln und Außenausstattung aus Edelholz. Die Stücke sind für Architektur gedacht, die Material sichtbar macht.',
		'Novoferm'           => 'Novoferm liefert Funktions- und Brandschutztüren in Serie und als Sonderanfertigung. Geprüfte Sicherheit trifft auf schlichtes Design für Gewerbe, Wohnbau und öffentliche Gebäude.',
	);
}

/** Zweiter Absatz je Bereich. */
function prezium_demo_bereich_text() {
	return array(
		'Türen'           => 'Alle Türen sind in mehreren Oberflächen und Maßen erhältlich. Zargen, Beschläge und Schallschutz werden projektbezogen abgestimmt. Preise erhalten Sie auf Anfrage, gern mit Muster für Architekten und Planer.',
		'Parkett'         => 'Das Parkett ist verklebt oder schwimmend verlegbar und für Fußbodenheizung geeignet. Auf Wunsch liefern wir Muster, Pflegeset und eine verlegefertige Mengenberechnung.',
		'Fenster'         => 'Die Fenster werden nach Aufmaß gefertigt und mit Dreifachverglasung geliefert. Farben, Griffe und Schallschutzklassen sind frei wählbar. Montage und Einbaudetails stimmen wir mit Ihrem Planer ab.',
		'Terrassendielen' => 'Die Dielen sind für den Außenbereich ausgelegt und verfügen über rutschhemmende Oberflächen. Befestigung und Unterkonstruktion sind im Lieferumfang auf Anfrage enthalten.',
		'Möbel'           => 'Die Möbel entstehen in kleiner Serie, Maße und Oberflächen sind anpassbar. Lieferzeiten und Preise nennen wir Ihnen gern nach einer kurzen Anfrage.',
		'Licht'           => 'Die Leuchten sind dimmbar und mit warmweißem LED-Modul ausgestattet. Weitere Lichtfarben und Sonderlängen sind auf Anfrage möglich.',
		'Sonstiges'       => 'Das Produkt ergänzt unsere Kollektionen für Wand, Boden und Raumakustik. Muster und technische Datenblätter stellen wir registrierten Fachkunden zur Verfügung.',
	);
}

/**
 * Produkte: Titel, Bereich, Marke, Artikelnr., Material, Maße, Ausführung, Bilddatei, Kurztext, Kurzfassung.
 */
function prezium_demo_products() {
	return array(
		array( 'Linea Zimmertür', 'Türen', 'Cocif Porte', 'CP-1040', 'Eiche lackiert', '2100 × 900 mm', 'Drehtür', 'linea-zimmertuer', 'Eine ruhige Zimmertür mit durchlaufender Fläche und verdeckten Bändern.', 'Zimmertür in lackierter Eiche, flächenbündig.' ),
		array( 'Cortina Schiebetür', 'Türen', 'Cocif Porte', 'CP-2210', 'Glas', '2400 × 1000 mm', 'Schiebetür', 'cortina-schiebetuer', 'Die Schiebetür aus Glas trennt Räume, ohne sie zu schließen. Der Laufschienenkasten verschwindet in der Decke.', 'Raumhohe Glasschiebetür mit verdeckter Schiene.' ),
		array( 'Stahl-Brandschutztür T30', 'Türen', 'Novoferm', 'NF-3005', 'Stahl', '2000 × 1000 mm', 'Drehtür', 'stahl-brandschutztuer', 'Geprüfte Brandschutztür T30 für Treppenhäuser, Technikräume und Gewerbe. Die Oberfläche ist pulverbeschichtet.', 'Brandschutztür T30 aus Stahl, pulverbeschichtet.' ),
		array( 'Bianco Designtür', 'Türen', 'Cocif Porte', 'CP-1055', 'Weißlack matt', '2100 × 900 mm', 'Drehtür', 'bianco-designtuer', 'Matt weiß lackiert, ohne sichtbare Zarge. Die Tür wirkt wie eine Wandfläche und eignet sich für zurückhaltende Interieurs.', 'Weiße Designtür, zargenlos.' ),
		array( 'Anthrazit Ganzglastür', 'Türen', 'Cocif Porte', 'CP-2240', 'Stahl, Glas', '2100 × 900 mm', 'Drehtür', 'anthrazit-glastuer', 'Schmaler Stahlrahmen in Anthrazit mit Klarglas. Ideal für Büros, Lofts und Wohnküchen.', 'Ganzglastür mit schmalem Stahlrahmen.' ),
		array( 'Nussbaum Stiltür', 'Türen', 'Cocif Porte', 'CP-1090', 'Nussbaum furniert', '2100 × 850 mm', 'Drehtür', 'nussbaum-stiltuer', 'Furnierte Tür mit feinen Fräsfugen. Das Nussbaumfurnier wird im Bild gespiegelt verlegt und mit Hartöl behandelt.', 'Nussbaumtür mit Fräsfugen.' ),
		array( 'Fischgrät Eiche Natur', 'Parkett', 'Ali Parquet', 'AP-5120', 'Eiche', '14 mm Stärke', 'Geölt', 'fischgraet-eiche', 'Klassisches Fischgrätmuster in naturgeölter Eiche. Die Dielen sind gebürstet und haben eine lebendige Struktur.', 'Fischgrätparkett in Eiche, naturgeölt.' ),
		array( 'Landhausdiele Nussbaum', 'Parkett', 'Aeterno Parquet', 'AE-6044', 'Nussbaum', '20 mm Stärke', 'Lackiert', 'landhausdiele-nussbaum', 'Breite Landhausdielen in dunklem Nussbaum. Die matte Lackierung betont die Maserung.', 'Landhausdiele aus Nussbaum, matt lackiert.' ),
		array( 'Räuchereiche Dielen', 'Parkett', 'Ali Parquet', 'AP-5180', 'Eiche geräuchert', '15 mm Stärke', 'Geölt', 'raeuchereiche-dielen', 'Durch Räuchern erhält die Eiche eine tiefe, gleichmäßige Färbung, die nicht auf einer Beschichtung beruht.', 'Geräucherte Eiche, Dreischichtdiele.' ),
		array( 'Fischgrät Nussbaum', 'Parkett', 'Aeterno Parquet', 'AE-6070', 'Nussbaum', '14 mm Stärke', 'Geölt', 'fischgraet-nussbaum', 'Fischgrätmuster in Nussbaum für repräsentative Räume. Die Verlegung erfolgt im Verband mit Passstücken an den Rändern.', 'Fischgrät in Nussbaum, geölt.' ),
		array( 'Holz-Alu-Fenster Serie 90', 'Fenster', 'Ravaioli Falegnami', 'RF-7001', 'Holz-Alu', 'nach Maß', 'Dreh-Kipp', 'holz-alu-fenster', 'Innen Holz, außen Aluminium. Das Fenster verbindet Wärme im Raum mit wetterfester Außenhaut.', 'Holz-Alu-Fenster mit Dreifachverglasung.' ),
		array( 'Kunststoff-Alu Fenster Serie 70', 'Fenster', 'Ravaioli Falegnami', 'RF-7040', 'Kunststoff-Alu', 'nach Maß', 'Dreh-Kipp', 'kunststoff-alu-fenster', 'Pflegeleichte Kombination für Neubau und Sanierung. Die Profile sind schlank und dämmen sehr gut.', 'Kunststoff-Alu-Fenster, pflegeleicht.' ),
		array( 'Hebeschiebetür Panorama', 'Fenster', 'Ravaioli Falegnami', 'RF-7090', 'Aluminium', 'bis 6000 × 2800 mm', 'Hebeschiebe', 'hebeschiebetuer-panorama', 'Große Glasflächen mit minimalen Rahmen. Die Hebeschiebetür öffnet Wohnräume zum Garten.', 'Panorama-Hebeschiebetür bis 6 m Breite.' ),
		array( 'Thermoesche Terrasse', 'Terrassendielen', 'Ali Parquet', 'AP-8030', 'Esche', '26 mm Stärke', 'Natur', 'thermoesche-terrasse', 'Thermisch modifizierte Esche ist formstabil und ohne Chemie dauerhaft. Die Oberfläche vergraut mit der Zeit.', 'Terrassendiele aus Thermoesche.' ),
		array( 'Bangkirai Terrassendiele', 'Terrassendielen', 'Ali Parquet', 'AP-8060', 'Bangkirai', '25 mm Stärke', 'Glatt', 'bangkirai-terrasse', 'Das harte Tropenholz ist langlebig und widerstandsfähig. Wir beziehen es ausschließlich aus zertifizierten Quellen.', 'Terrassendiele aus Bangkirai, FSC-zertifiziert.' ),
		array( 'WPC Dielen Graphit', 'Terrassendielen', 'Aeterno Parquet', 'AE-8120', 'WPC', '23 mm Stärke', 'Gebürstet', 'wpc-graphit', 'Holz-Kunststoff-Verbund mit gebürsteter Oberfläche. Splitterfrei, pflegearm und in Graphit sehr ruhig im Auftritt.', 'WPC-Diele in Graphit, splitterfrei.' ),
		array( 'Outdoor Lounge Teak', 'Möbel', 'Aeterno Parquet', 'AE-9010', 'Teak', '220 × 90 cm', 'Natur', 'outdoor-lounge-teak', 'Lounge-Sofa aus Teak mit wetterfesten Polstern. Das Holz entwickelt mit der Zeit eine silbergraue Patina.', 'Teak-Lounge für Terrasse und Garten.' ),
		array( 'Esstisch Eiche massiv', 'Möbel', 'Aeterno Parquet', 'AE-9040', 'Eiche massiv', '240 × 100 cm', 'Geölt', 'esstisch-eiche', 'Massiver Tisch aus durchgehenden Eichenbohlen. Die Kanten sind sanft gebrochen, die Platte ist 40 mm stark.', 'Massivholztisch aus Eiche.' ),
		array( 'Sideboard Nussbaum', 'Möbel', 'Aeterno Parquet', 'AE-9075', 'Nussbaum furniert', '180 × 45 × 70 cm', 'Geölt', 'sideboard-nussbaum', 'Sideboard mit vier Türen und einem fein gearbeiteten Fugenbild. Die Türen sind mit Softclose ausgestattet.', 'Sideboard aus Nussbaum, vier Türen.' ),
		array( 'Schienenleuchte Prisma', 'Licht', 'Ravaioli Falegnami', 'RF-1120', 'Aluminium', 'Ø 60 mm', 'Schwarz matt', 'schienenleuchte-prisma', 'Zylindrischer Strahler für Stromschienen mit schwenkbarem Kopf. Ideal für Galerien, Showrooms und Wohnräume.', 'Schienenstrahler aus Aluminium, schwarz matt.' ),
		array( 'Pendelleuchte Sfera', 'Licht', 'Ravaioli Falegnami', 'RF-1150', 'Opalglas', 'Ø 40 cm', 'Weiß', 'pendelleuchte-sfera', 'Kugelförmige Pendelleuchte aus mundgeblasenem Opalglas. Sie gibt weiches, blendfreies Licht und ist dimmbar.', 'Pendelleuchte aus Opalglas, dimmbar.' ),
		array( 'Wandleuchte Linea', 'Licht', 'Ravaioli Falegnami', 'RF-1180', 'Aluminium', 'H 90 cm', 'Schwarz', 'wandleuchte-linea', 'Schlanke Wandleuchte mit indirektem Licht. Das Modul sitzt im oberen Ende und strahlt sanft an die Wand.', 'Schlanke Wandleuchte mit indirektem Licht.' ),
		array( 'Akustikpaneel Eiche', 'Sonstiges', 'Aeterno Parquet', 'AE-0210', 'Eiche, Filz', '2400 × 600 mm', 'Lamellen', 'akustikpaneel-eiche', 'Wandpaneel mit Eichenlamellen auf Akustikfilz. Es verbessert die Raumakustik und gibt Wänden Tiefe.', 'Akustikpaneel aus Eichenlamellen.' ),
		array( 'Basalt Fliese', 'Sonstiges', 'Novoferm', 'NF-0450', 'Naturstein', '600 × 600 mm', 'Gestockt', 'basalt-fliese', 'Natursteinfliese aus Basalt mit gestockter Oberfläche. Hart, frostsicher und für Innen- und Außenbereiche geeignet.', 'Basaltfliese 60 × 60 cm, gestockt.' ),
	);
}

function prezium_demo_pages() {
	return array(
		'Über uns'              => "Prezium GmbH bringt europäische Manufakturen und anspruchsvolle Projekte zusammen.\n\nWir zeigen eine kuratierte Auswahl an Türen, Parkett, Fenstern, Terrassendielen, Möbeln und Leuchten. Unser Showroom ist digital: Sie stöbern in Ruhe, fragen den Preis an und erhalten ein persönliches Angebot.\n\nUnser Anspruch steckt im Namen: Präzision in Material, Verarbeitung und Beratung. Das ist ein Demo-Text.",
		'Kontakt'               => "Prezium GmbH\nMusterstraße 1\n51373 Leverkusen\n\nTelefon: +49 000 000000\nE-Mail: info@example.com\n\nÖffnungszeiten: Mo–Fr 9–17 Uhr. (Demo-Adresse, bitte vor dem Livegang ersetzen.)",
		'Architekten & Designer' => "Für Architekten, Designer und Bauunternehmen bieten wir einen geschützten Bereich.\n\nNach der Freischaltung finden Sie dort Preislisten, technische Dokumentation, Montageanleitungen und CAD/BIM-Modelle zu allen Produkten. Die Registrierung ist kostenlos und wird von uns persönlich geprüft.\n\nDas ist ein Demo-Text.",
		'Impressum'             => 'Platzhalter. Rechtstexte werden vor dem Livegang von einer Fachkanzlei erstellt.',
		'Datenschutz'           => 'Platzhalter. Rechtstexte werden vor dem Livegang von einer Fachkanzlei erstellt.',
	);
}
