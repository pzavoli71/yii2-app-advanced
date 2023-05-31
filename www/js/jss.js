

;var CIS_JSS = (function ($) {


	// numero di colonne, numero colonne griglia
	var cols_base = [2, 3, 4, 5, 6, 12 ],
		cols_grid = 6,
		cellPadding = 1; // percentuale margini

	/*+ "    -webkit-box-sizing: border-box;"
	+ "    -moz-box-sizing: border-box;"
	+ "    box-sizing: border-box;"
*/

	var style = ""
+ "  *, *:after, *:before {"
+ "  }"

+ "  row:before, .row:after {"
+ "      content: '';"
+ "      display: table;"
+ "      clear: both;"
+ "  }"

+ "  .row {"
+ "      box-sizing: border-box;"
+ "      position: relative;"
+ "      display: -ms-flexbox;"
+ "      display: flex;"
+ "      -ms-flex: 0 1 auto;"
+ "      flex: 0 1 auto;"
+ "      -ms-flex-direction: row;"
+ "      flex-direction: row;"
+ "      -ms-flex-wrap: wrap;"
+ "      flex-wrap: wrap;"
+ "  }"

+ "  .row.reverse {"
+ "      -ms-flex-direction: row-reverse;"
+ "      flex-direction: row-reverse"
+ "  }"

+ "  .grid > div, [class*='col-']  {"
+ "      width:100%;"
+ "      transition: width 0.5s ease;"
+ "      display:inline-block;"
+ "      margin-left:-4px;"
+ "      zoom: 1;"
+ "      letter-spacing: normal;"
+ "      word-spacing: normal;"
+ "      vertical-align: top;"
+ "      -moz-box-sizing: border-box;"
+ "      box-sizing: border-box;"
+ "      position: relative;"
+ "      -ms-flex: 0 0 auto;"
+ "      flex: 0 0 auto;"
+ "  }"
//+ "      padding: 0 " +  cellPadding + "% 0 " +  cellPadding + "%;"

+ ".wrap::after {             "
+ "  content:\"\"; "
+ "  clear: both;            "
+ "  display: block;         "
+ "}                         "

+ "  [class*='pull-'] {"
+ "      float:right;"
+ "  }"

+ "  @-ms-viewport {"
+ "      width: device-width;"
+ "  }"

+ "  @media print {"
+ "      .hidden-print {"
+ "          display: none !important;"
+ "      }"
+ "  }"

+ "";

	// suddivisione larghezza (em) dei tipi di schermo
	var screens = {
		''  : { w :  0 }, // tutti gli schermi
		's' : { w :  0 }, // small: smartphone
		'm' : { w : 35 }, // medium: tablet
		'l' : { w : 60 }, // large: desktop
	};


	// per ogni tipo di schermo
	for (var s in screens) {

		var p = '' , sz = '', r = '';
		var screen = (s !== '') ? '-' + s : '';

		// griglia con un numero di colonne dentro
		for (var n = 1; n <= 6; n++) {

			// .grid.cols-[s,m,l]-[1-6]
			var rule = '.grid.cols' + screen + '-' + n;

			// si applica agli elementi interni di tipo:
			p += rule + ' > div ' +

			// suddivido in percentuale la larghezza degli elementi contenuti
			'{' +
				'width:' + ( (100 / n) )  + '%;' +
			'}';

		}


		// regole
		r = {

		// nascondi elemento
		'.hidden' :
        '	display : none;                 '
		,

		// mostra elemento
		'.visible' :
		'	display : inline-block;         '
		,

		// all'inizio della riga
		'.start' :
		'	justify-content : flex-start;   '+
		'	text-align      : start;        '
		,

		// al centro della riga
		'.center' :
		'	justify-content : center;       '+
		'	text-align      : center;       '
		,

		// alla fine della riga
		'.end' :
		'	justify-content : flex-end;     '+
		'	text-align      : end;          '
		,

		// in alto
		'.top' :
		'	align-items    : flex-start;    '
        ,

        // in mezzo
		'.middle' :
		'	align-items    : center;        '
		,

		// in basso
		'.bottom' :
		'	align-items    : flex-end;      '
		,

		// attorno
		'.around' :
		'	justify-content : space-around; '
		,

		// in mezzo
		'.between' :
		'	justify-content : space-between;'
		,

		// per primo
		'.first' :
		'	order : -1;                     '
		,

		// per ultimo
		'.last' :
		'	order : 1;                      '
		,

		// inverti ordine
		'.reverse' :
		'	flex-direction     : column-reverse; '
		};

		for (var rule in r) {
			p += rule + screen + '{' + r[rule] + '}';
		}

		// regole in base alle colonne
		for (var i = 0; i < cols_base.length; i++) {

			var base = cols_base[i];

			for (var b = 1; b <= base; b++) {

				var width =  ((100 / base) * b) + '%;';

				var r = {

					// larghezza colonna
					'.col'    : 'width : ' + width ,

					// posizione da sinistra
					'.push'   : 'left  : ' + width ,

					// posizione da destra
					'.pull'   : 'right : ' + width ,

					// spazio da sinistra
					'.offset' : 'margin-left : ' + width

				};

				for (var rule in r) {
					p += rule + screen + '-' +
						 b + '-' + base +
						 '{' +
							r[rule] +
						 '}';
				}
			}

		}

		if (s !== '') {
			style += ' @media screen and ( min-width:' + screens[s].w + 'em)' +
			       ' {' + p + ' } ' ;
		} else {
			style += p ;
		}

	}


	//var style_css = jss.create.createStyleSheet(style).toString();

	// Compile styles,  insert it into DOM.
	$('head').append($('<style>').append(style));





	// assegno una classe generale in base alla larghezza dello schermo
	function responsiveBody() {
		for (var s in screens) {
			var w = screens[s].w * 16;

		    if($(window).width() > w) {
		        $('body').attr('class',
		        		function(i, c){  return c.replace(/(^|\s)screen-\S+/g, ''); });
		        $('body').addClass('screen-' + s);
                        if (s === 's')
                            cambiaTablesScreens();
		    }
		}
                // Adesso aggiungo la classe ai body di tutte le iframe della pagina
	}


	function onScrollChangeClass() {
		if ($(window).scrollTop() > 50)
	    	$('body').addClass('scrolled');
	    else
	    	$('body').removeClass('scrolled');

	    return true;
	}
        
        function cambiaTables() {
            $('.tabLista').replaceWith(function() {

                var $th = $(this).find('th'); // get headers
                var th = $th.map(function() {
                    return $(this).text();
                }).get(); // and their values

                $th.closest('tr').remove(); // then remove them

                var $d = $('<div class="tabLista">', { 'class': 'box' });

                $('tr', this).each(function(i, el) {
                    var $div = $('<div>', {'class': 'inner'}).appendTo($d);
                    $('td', this).each(function(j, el) {
                        var closed = false;
                        if ( $(this).hasClass('closed'))
                            closed = true;
                        var n = j + 1;
                        var $row = $('<div class="td' + (closed?' closed':'') + '">', {
                            'class': 'row-' + n
                        });
                        $row.append(
                        $('<span>', {
                            'class': 'label-' + n,
                            text: th[j]
                        }), ' : ', $('<span>', {
                            'class': 'data-' + n,
                            html: $(this).html()
                        })).appendTo($div);
                    });
                });

                return $d;
            });
        }
        
        function cambiaTablesScreens() {
            $('.tabLista').each(function() {
               $(this).removeClass('tabLista').addClass('tabListas');
            });
        }
	$(window).on('resize', function() {
		responsiveBody();
	});

	$(document).ready( function() {
		responsiveBody();
		$(window).bind('scroll', onScrollChangeClass );
	});


} (jQuery));
