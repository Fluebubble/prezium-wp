<?php
/**
 * Ausführliche Demo-Artikel zu den Projekten. Platzhalter <!--prez-img:N--> wird beim Anzeigen
 * durch das N-te Galeriebild ersetzt (nur wenn vorhanden). Bildnamen: assets/demo-projects/projects/<slug>-N.webp
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function prezium_demo_project_articles() {
	return array(
		'villa-mit-doppelraum' => array(
			'gallery' => array( 'Der Wohnbereich öffnet sich über zwei Geschosse.', 'Eichendielen und Bronze: ein ruhiges Materialpaar.', 'Die Pendelleuchte setzt am Abend den Mittelpunkt.' ),
			'quote'   => array( 'Wir wollten ein Haus, das man nicht erklären muss. Man betritt es und versteht es.', 'Bauherrin, Villa (Demo)' ),
			'html'    => "<p class=\"lead\">Ein Haus, das den Raum in die Höhe öffnet: Eingang, Wohnen und Galerie verschmelzen zu einer einzigen, großzügigen Geste.</p>
<h2>Die Ausgangslage</h2>
<p>Die Bauherren wünschten sich ein Zuhause für eine Familie mit häufigen Gästen. Das Grundstück am Hang bot Weitblick, verlangte aber nach einem Haus, das sich zum Licht hin öffnet und zugleich Ruhe ausstrahlt. Der Architekt entschied sich für einen zweigeschossigen Wohnraum, den „Doppelraum“, um den sich alle weiteren Räume gruppieren.</p>
<h2>Das Konzept</h2>
<p>Ein großer Raum braucht Zurückhaltung. Deshalb beschränkt sich die Materialpalette auf wenige, ehrliche Werkstoffe: Eiche, Bronze, Glas. Nichts konkurriert miteinander, alles darf altern. Die Wege im Haus folgen einer klaren Achse, vom Eingang über die Galerie bis zur Gartenfront.</p>
<!--prez-img:1-->
<h2>Die Materialien</h2>
<p>Den Boden bildet die <strong>Eiche Landhausdiele Natur</strong>: 2,2 Meter lange, breite Dielen in Naturöl, die sich als ruhiger Teppich durch alle Ebenen ziehen. Die Maserung bleibt sichtbar, kleine Äste sind erwünscht. So wirkt selbst eine Fläche von über 100 Quadratmetern warm und lebendig.</p>
<p>Die raumhohen <strong>Holz-Alu-Fenster Alba</strong> holen das Tageslicht tief ins Haus. Innen zeigen sie Eiche, außen schützt bronzefarben eloxiertes Aluminium. Die schlanken Profile lassen die Glasfläche größer erscheinen, als sie ist.</p>
<!--prez-img:2-->
<blockquote><p>Wir wollten ein Haus, das man nicht erklären muss. Man betritt es und versteht es.</p><cite>Bauherrin, Villa (Demo)</cite></blockquote>
<h2>Das Licht</h2>
<p>Am Tag arbeitet das Haus mit dem Himmel, am Abend übernimmt die <strong>Pendelleuchte Corona</strong> die Bühne. Mit 90 Zentimetern Durchmesser hängt sie wie eine Skulptur im Doppelraum. Das gebürstete Messing nimmt das warme Licht auf und gibt es weich an Wände und Boden zurück. Die <strong>Stehleuchte Linea</strong> ergänzt die Szene an der Galerie.</p>
<!--prez-img:3-->
<h2>Die Umsetzung</h2>
<p>Die Dielen wurden nach einer Akklimatisierungszeit von zwei Wochen verlegt, die Fensterlieferung exakt nach Aufmaß geplant. Zwischen Fensterbauer, Parkettleger und Elektriker gab es feste Termine, damit Staub und Nacharbeiten die Oberflächen nicht beeinträchtigen. Das Ergebnis: eine Bauzeit ohne Verzug und Oberflächen ohne Kompromisse.</p>
<h2>Das Ergebnis</h2>
<p>Aus drei Materialien und wenigen Leuchten ist ein Raum entstanden, der morgens hell, abends warm und zu jeder Zeit ruhig ist. Die Familie nutzt den Doppelraum als Mittelpunkt des Alltags, die Gäste bleiben länger, als sie geplant hatten.</p>
<p><em>Dies ist ein Demo-Text.</em></p>",
		),
		'penthouse-mit-stadtblick' => array(
			'gallery' => array( 'Fischgrät-Parkett im Wohnbereich mit Blick auf die Skyline.', 'Das Sofa Orsa und der Couchtisch Pietra im Zusammenspiel.', 'Abendstimmung: Sessel Lume vor der Glasfront.' ),
			'quote'   => array( 'Die Stadt liegt uns zu Füßen. Drinnen wollte ich das genaue Gegenteil: Stille.', 'Eigentümer, Penthouse (Demo)' ),
			'html'    => "<p class=\"lead\">Über den Dächern der Stadt wird die Wohnung zum ruhigen Gegenpol: warme Materialien, klare Linien, ein Blick, der nie langweilig wird.</p>
<h2>Die Ausgangslage</h2>
<p>Das Penthouse im obersten Geschoss bietet 260 Quadratmeter und eine umlaufende Glasfront. Der Eigentümer, viel unterwegs und beruflich im Rampenlicht, wünschte sich ein Zuhause, das zur Ruhe kommen lässt. Die Herausforderung: Eine Wohnung mit so viel Aussicht darf drinnen nicht zu laut werden.</p>
<h2>Das Konzept</h2>
<p>Die Gestaltung folgt dem Prinzip „weniger, aber besser“. Gedeckte Töne, wenige große Möbel und ein durchgehender Boden schaffen einen ruhigen Hintergrund, vor dem die Skyline wirkt wie ein Bild. Alle Stücke stehen so, dass Wege frei bleiben und der Raum atmen kann.</p>
<!--prez-img:1-->
<h2>Der Boden</h2>
<p>Das <strong>Fischgrät Räuchereiche</strong> zieht sich durch alle Räume. Das klassische Muster in geräucherter Eiche bringt Tiefe und Bewegung in die Flächen, ohne unruhig zu werden. Die Elemente wurden von Hand verlegt und mit Hartwachsöl behandelt, damit die Oberfläche offenporig und angenehm bleibt.</p>
<!--prez-img:2-->
<h2>Die Möbel</h2>
<p>Das <strong>Sofa Orsa</strong> ist das Zentrum des Wohnbereichs: tief, weich, in Naturleinen auf einem massiven Buchensockel. Gegenüber stehen zwei <strong>Sessel Lume</strong> in Bouclé, deren runde Form die Strenge der Architektur bricht. Der <strong>Couchtisch Pietra</strong> aus einem Stück Naturstein verankert die Gruppe: schwer, ruhig, ein Unikat.</p>
<blockquote><p>Die Stadt liegt uns zu Füßen. Drinnen wollte ich das genaue Gegenteil: Stille.</p><cite>Eigentümer, Penthouse (Demo)</cite></blockquote>
<!--prez-img:3-->
<h2>Die Umsetzung</h2>
<p>Die Anlieferung im obersten Geschoss verlangte eine genaue Planung: Maße der Aufzüge, Zeitfenster, Schutz der Böden. Alle Möbel wurden vorab am Plan positioniert, sodass vor Ort nichts verschoben werden musste.</p>
<h2>Das Ergebnis</h2>
<p>Ein Zuhause, das abends den Lärm der Stadt ausblendet und trotzdem jeden Moment der Aussicht zulässt. Die Kombination aus Räuchereiche, Leinen und Stein wirkt zeitlos und wird mit den Jahren eher schöner.</p>
<p><em>Dies ist ein Demo-Text.</em></p>",
		),
		'haus-im-morgennebel' => array(
			'gallery' => array( 'Die Terrasse im Morgennebel: Thermoesche und grauer Bambus.', 'Die Hebeschiebetür verbindet Wohnraum und Terrasse.', 'Detail der Diele: gebürstete Oberfläche, warme Farbe.' ),
			'quote'   => array( 'Morgens öffnen wir die Tür, und der Wald ist im Wohnzimmer.', 'Bauherr, Haus im Morgennebel (Demo)' ),
			'html'    => "<p class=\"lead\">Ein Haus am Waldrand, das sich der Landschaft öffnet. Die Terrasse verlängert den Wohnraum nach draußen und altert in Würde.</p>
<h2>Die Ausgangslage</h2>
<p>Am Rand der Eifel liegt das Grundstück oft im Morgennebel. Die Bauherren wollten diesen Moment nicht durch Fenster betrachten, sondern erleben: mit einem Frühstück draußen, mit offener Tür, mit dem Duft von feuchtem Holz. Daraus entstand die Aufgabe, Innen und Außen so nahtlos wie möglich zu verbinden.</p>
<h2>Das Konzept</h2>
<p>Das Haus ist niedrig und lang gestreckt, die Terrasse nimmt die ganze Gartenseite ein. Bodenhöhe innen und außen sind identisch, die Schwelle verschwindet. Farben und Materialien entstammen der Umgebung: Holz, Anthrazit, Grau.</p>
<!--prez-img:1-->
<h2>Die Terrasse</h2>
<p>Für den Hauptbereich wurde die <strong>Terrassendiele Thermoesche</strong> gewählt. Durch die thermische Behandlung erhält die Esche einen warmen, dunklen Farbton und eine hohe Formstabilität, ganz ohne chemische Zusätze. Eine zweite Zone, nah am Haus, ist mit der <strong>Terrassendiele Bambus Grau</strong> belegt: verdichteter Bambus, besonders hart und mit einer geriffelten Oberfläche, die auch bei Nässe sicheren Halt bietet.</p>
<!--prez-img:2-->
<blockquote><p>Morgens öffnen wir die Tür, und der Wald ist im Wohnzimmer.</p><cite>Bauherr, Haus im Morgennebel (Demo)</cite></blockquote>
<h2>Die Verbindung</h2>
<p>Die <strong>Hebeschiebetür Nebbia</strong> misst bis zu 4,5 Meter in der Breite und lässt sich mit einer Hand bewegen. Die schmalen anthrazitfarbenen Rahmen nehmen sich zurück, sodass der Blick ungebrochen in den Garten geht. Dichtungen und Dreifachverglasung sorgen dafür, dass im Winter trotzdem alles warm bleibt.</p>
<!--prez-img:3-->
<h2>Die Umsetzung</h2>
<p>Die Unterkonstruktion der Terrasse wurde mit Gefälle und Belüftung geplant, damit das Wasser ablaufen kann und das Holz trocknet. Die Dielen wurden mit verdeckter Befestigung verlegt: Es sind keine Schrauben zu sehen, die Fläche wirkt wie aus einem Guss.</p>
<h2>Das Ergebnis</h2>
<p>Eine Terrasse, die mit den Jahren eine silbrige Patina entwickelt und das Haus mit der Landschaft verwachsen lässt. Im Sommer ist sie das zweite Wohnzimmer, im Herbst die Bühne für den Nebel.</p>
<p><em>Dies ist ein Demo-Text.</em></p>",
		),
		'boutique-hotel-lobby' => array(
			'gallery' => array( 'Die Flügeltür Aurum als Auftakt der Lobby.', 'Kronleuchter Cascata über dem Empfangsbereich.', 'Lounge-Sessel Salone und Wandleuchte Arco.' ),
			'quote'   => array( 'Der erste Eindruck dauert Sekunden, aber er bleibt. Genau dafür ist die Lobby da.', 'Hotelleitung, Boutique-Hotel (Demo)' ),
			'html'    => "<p class=\"lead\">Die Lobby eines kleinen Hotels, die Gästen den Atem nimmt, bevor sie ein Wort gesagt haben: Bronze, Walnuss, Kristall und Marmor.</p>
<h2>Die Ausgangslage</h2>
<p>Ein Stadthaus im Zentrum von Köln wurde zum Boutique-Hotel mit 24 Zimmern umgebaut. Die Lobby sollte mehr sein als ein Durchgang: Visitenkarte, Wohnzimmer und Treffpunkt in einem. Die Vorgabe der Betreiber lautete, dass Gäste das Haus nicht als Hotel, sondern als Gastgeber wahrnehmen.</p>
<h2>Das Konzept</h2>
<p>Wenige Materialien, perfekt verarbeitet. Die Lobby arbeitet mit warmen Kontrasten: dunkles Holz gegen helles Licht, matte Oberflächen gegen glänzendes Messing. Jedes Objekt ist eine bewusste Entscheidung.</p>
<!--prez-img:1-->
<h2>Der Auftakt</h2>
<p>Die <strong>Flügeltür Aurum</strong> bildet den Eingang: zweiflügelig, 2,8 Meter hoch, in Nussbaum mit Bronzeakzent und langen Griffen aus demselben Metall. Beim Öffnen spürt man das Gewicht und die Qualität, ein Versprechen für alles, was dahinter folgt.</p>
<h2>Das Licht</h2>
<p>Über dem Empfang hängt der <strong>Kronleuchter Cascata</strong>: eine Kaskade aus mundgeblasenem Kristall auf poliertem Messing. Er bricht das Licht in tausend Reflexe und wirft sie auf Marmor und Wände. An den Seiten sorgt die <strong>Wandleuchte Arco</strong> für weiches, indirektes Licht, damit der Raum auch abends nie hart wirkt.</p>
<!--prez-img:2-->
<blockquote><p>Der erste Eindruck dauert Sekunden, aber er bleibt. Genau dafür ist die Lobby da.</p><cite>Hotelleitung, Boutique-Hotel (Demo)</cite></blockquote>
<h2>Die Möbel</h2>
<p>Der <strong>Empfangstresen Atrium</strong> ist das Herzstück: massives Nussbaumholz, geölt, mit einer Platte aus weißem Marmor. Daneben laden die <strong>Lounge-Sessel Salone</strong> in smaragdgrünem Samt zum Bleiben ein. Die hohe Rückenlehne schützt vor Blicken und macht aus jedem Sessel einen kleinen Rückzugsort.</p>
<!--prez-img:3-->
<h2>Die Umsetzung</h2>
<p>Gebaut wurde in Etappen, damit das Hotel pünktlich eröffnen konnte. Türen und Möbel wurden maßgefertigt und als fertig montierte Einheiten geliefert, die Leuchten erst in der letzten Woche gehängt, um Schäden zu vermeiden.</p>
<h2>Das Ergebnis</h2>
<p>Eine Lobby, über die Gäste sprechen, noch bevor sie ihr Zimmer gesehen haben. Das Haus berichtet von auffallend vielen Stammgästen und Bewertungen, die den ersten Eindruck ausdrücklich loben.</p>
<p><em>Dies ist ein Demo-Text.</em></p>",
		),
	);
}
