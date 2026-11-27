<?php
/** Auswahl aus dem Schwesterkatalog (Slug => Bereich). Wird von inc/sergo-import.php verwendet. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function prezium_sergo_list() {
	$t = 'Türen'; $p = 'Parkett'; $d = 'Terrassendielen'; $m = 'Möbel'; $l = 'Licht';
	return array(
		'razdvizhnye-dveri-barausse-lume' => $t, 'razdvizhnye-dveri-pivato-scorrevoli-special' => $t,
		'razdvizhnye-dveri-tre-p-tre-piu-easy' => $t, 'razdvizhnye-dveri-res-luna' => $t,
		'razdvizhnye-dveri-lualdi-shoin' => $t, 'vhodnye-dveri-magnum-residence' => $t,
		'vhodnye-dveri-bauxt-superior' => $t, 'vhodnye-dveri-oikos-nova' => $t,
		'vhodnye-dveri-effepi-due-ante' => $t, 'vhodnye-dveri-keratur-exclusiv-line' => $t,
		'parketnaja-doska-i-parket-avance-floors-madara' => $p, 'parketnaja-doska-i-parket-avance-floors-alta' => $p,
		'parketnaja-doska-i-parket-avance-floors-toledo' => $p, 'parketnaja-doska-i-parket-avance-floors-tanum' => $p,
		'parketnaja-doska-i-parket-avance-floors-laguna' => $p, 'parketnaja-doska-i-parket-avance-floors-preto' => $p,
		'parketnaja-doska-i-parket-avance-floors-burgos' => $p, 'parketnaja-doska-i-parket-avance-floors-mena' => $p,
		'parketnaja-doska-i-parket-avance-floors-manos' => $p, 'parketnaja-doska-i-parket-t-g-wood-10-mm-dual-1-strip' => $p,
		'parketnaja-doska-i-parket-t-g-wood-12-mm-dual-1-strip' => $p, 'parketnaja-doska-i-parket-t-g-wood-15-mm-dual-1-strip' => $p,
		'parketnaja-doska-i-parket-t-g-wood-15-mm-design-1-strip' => $p, 'parketnaja-doska-i-parket-t-g-wood-15-mm-wide-1-strip' => $p,
		'terrasnaja-doska-moso-grad-bamboo-x-treme-decking' => $d, 'terrasnaja-doska-moso-bamboo-x-treme-decking-for-grad-system' => $d,
		'kreslo-tacchini-giulia' => $m, 'kreslo-tacchini-sancarlo' => $m, 'kreslo-tacchini-shelter' => $m,
		'divan-tacchini-quadro' => $m, 'divan-tacchini-sliding' => $m, 'divan-tacchini-montevideo' => $m,
		'krovat-caccaro-groove' => $m, 'krovat-caccaro-filesse' => $m, 'krovat-alivar-haero-daybed' => $m,
		'komod-vysokij-rugiano-zion' => $m, 'komod-rugiano-zion' => $m, 'komod-rugiano-vogue' => $m,
		'stul-tacchini-doodle' => $m, 'stul-tacchini-babela' => $m, 'stul-tacchini-pigreco' => $m, 'konsol-rugiano-zoe' => $m,
		'ljustra-manooi-wissh' => $l, 'ljustra-manooi-voile' => $l, 'ljustra-manooi-vague-3d' => $l,
		'ljustra-manooi-tondo' => $l, 'ljustra-manooi-su' => $l, 'torsher-vibia-north' => $l,
		'torsher-vibia-swing' => $l, 'torsher-vibia-warm' => $l, 'torsher-vibia-skan' => $l,
		'torsher-vibia-i-cono' => $l, 'nastolnaja-lampa-vibia-swing' => $l,
		'nastolnaja-lampa-cangini-tucci-pit-lampada-da-tavolo' => $l, 'nastolnaja-lampa-moooi-paper' => $l,
		'nastolnaja-lampa-artemide-logico' => $l, 'svetilnik-nastolnyj-slamp-liza-table' => $l,
		'svetilnik-nastennyj-manooi-silk' => $l,
	);
}

/** Produkttyp (aus dem Titelpräfix) => [Singular, Satz-Beschreibung]. */
function prezium_sergo_types() {
	return array(
		'Раздвижные двери'   => array( 'Schiebetür', 'Eine Schiebetür, die Raum spart und Räume großzügig verbindet.' ),
		'Входные двери'      => array( 'Haustür', 'Eine Eingangstür, die Sicherheit mit repräsentativem Auftritt verbindet.' ),
		'Паркетная доска'    => array( 'Parkett', 'Ein Parkett mit lebendiger Holzstruktur für Räume mit Charakter.' ),
		'Террасная доска'    => array( 'Terrassendiele', 'Eine Terrassendiele für den wetterfesten Außenbereich.' ),
		'Кресло'             => array( 'Sessel', 'Ein Sessel mit klarer Linie und hohem Sitzkomfort.' ),
		'Диван'              => array( 'Sofa', 'Ein Sofa, das Wohnräumen Ruhe und Haltung gibt.' ),
		'Кровать'            => array( 'Bett', 'Ein Bett, das das Schlafzimmer zum Mittelpunkt macht.' ),
		'Комод'              => array( 'Kommode', 'Eine Kommode mit viel Stauraum und edler Oberfläche.' ),
		'Стул'               => array( 'Stuhl', 'Ein Stuhl, der Design und Sitzkomfort verbindet.' ),
		'Консоль'            => array( 'Konsole', 'Eine Konsole als elegantes Highlight für Flur und Wohnraum.' ),
		'Люстра'             => array( 'Pendelleuchte', 'Eine Leuchte, die Räume zugleich ausleuchtet und gestaltet.' ),
		'Торшер'             => array( 'Stehleuchte', 'Eine Stehleuchte für stimmungsvolles, flexibles Licht.' ),
		'Настольная лампа'   => array( 'Tischleuchte', 'Eine Tischleuchte für gezieltes, warmes Licht.' ),
		'Светильник'         => array( 'Leuchte', 'Eine Leuchte mit skulpturaler Wirkung.' ),
	);
}

function prezium_sergo_countries() {
	return array(
		'Италия' => 'Italien', 'Германия' => 'Deutschland', 'Нидерланды' => 'Niederlande', 'Венгрия' => 'Ungarn',
		'Испания' => 'Spanien', 'Франция' => 'Frankreich', 'Китай' => 'China', 'Дания' => 'Dänemark',
		'Бельгия' => 'Belgien', 'Португалия' => 'Portugal', 'Польша' => 'Polen', 'Австрия' => 'Österreich',
		'Швеция' => 'Schweden', 'Великобритания' => 'Großbritannien', 'Украина' => 'Ukraine',
	);
}
