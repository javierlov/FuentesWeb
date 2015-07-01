<!-- at the beginning of the document's body configure and initialize the hint object -->

// configuration variable for the hint object, these setting will be shared among all hints created by this object
var HINTS_CFG = {
	'wise'       : true, // don't go off screen, don't overlap the object in the document
	'margin'     : 10, // minimum allowed distance between the hint and the window edge (negative values accepted)
	'gap'        : -7, // minimum allowed distance between the hint and the origin (negative values accepted)
	'align'      : 'brtl', // align of the hint and the origin (by first letters origin's top|middle|bottom left|center|right to hint's top|middle|bottom left|center|right)
	'css'        : 'wrapped', // a style class name for all hints, applied to DIV element (see style section in the header of the document)
	'show_delay' : 100, // a delay between initiating event (mouseover for example) and hint appearing
	'hide_delay' : 600, // a delay between closing event (mouseout for example) and hint disappearing
	'follow'     : false, // hint follows the mouse as it moves
	'z-index'    : 100, // a z-index for all hint layers
	'IEfix'      : false, // fix IE problem with windowed controls visible through hints (activate if select boxes are visible through the hints)
	'IEtrans'    : ['blendTrans(DURATION=.3)'], // [show transition, hide transition] - transition effects, only work in IE5+
	'opacity'    : 95 // opacity of the hint in %%
};

<?
$sql =
	"SELECT MAX(be_id)
		 FROM rrhh.rbe_beneficios
		WHERE be_fechabaja IS NULL";
$maxId = ValorSql($sql, 0);
?>
// text/HTML of the hints
var HINTS_ITEMS = [
<?
for ($i=0; $i<=$maxId; $i++) {
	$params = array(":id" => $i);
	$sql =
		"SELECT be_detalle
			 FROM rrhh.rbe_beneficios
			WHERE be_id = :id";
?>
	wrap2('<?= ValorSql($sql, "", $params)?>'),
<?
}
?>
	wrap2('')
];

// this custom function receives what's unique about individual hint and wraps it in the HTML template
function wrap (s_text, s_icon) {
	return '<table><tr><td rowspan="2"><img src="' + s_icon + '"></td><td colspan="2"><img src="/js/hint/img/pixel.gif" width="1" height="15" border="0"></td></tr><tr><td background="/js/hint/img/2.gif" height="28" nowrap>' + s_text + '</td><td><img src="/js/hint/img/4.gif"></td></tr></table>';
}

// multiple templates/functions can be used in the same page
function wrap2 (s_text) {
	return [
		'<table border="0" cellpadding="0" cellspacing="0">',
			'<tr>',
				'<td><img src="/js/hint/img/corner_tl.gif" width="10" height="10" /></td>',
				'<td style="background-image:url(/js/hint/img/side_t.gif);"></td>',
				'<td><img src="/js/hint/img/corner_tr.gif" width="10" height="10" /></td>',
			'</tr>',
			'<tr>',
				'<td style="background-image:url(/js/hint/img/side_l.gif);"></td>',
				'<td class="hintText">', s_text ,'</td>',
				'<td style="background-image:url(/js/hint/img/side_r.gif);"></td>',
			'</tr>',
			'<tr>',
				'<td><img src="/js/hint/img/corner_bl.gif" width="10" height="10" /></td>',
				'<td style="background-image:url(/js/hint/img/side_b.gif);"></td>',
				'<td><img src="/js/hint/img/corner_br.gif" width="10" height="10" /></td>',
			'</tr>',
		'</table>'
	].join('');
}

var myHint = new THints(HINTS_ITEMS, HINTS_CFG);