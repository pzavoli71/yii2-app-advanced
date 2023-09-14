/**
 * Funzioni informative, di debug, utilita generiche di basso livello.
 *
 * @module CIS
 * @requires Generale
 * @requires AppGlob
 */
;if (typeof(CIS)==="undefined")
  CIS = {};

/**
 * Classe contenente funzioni informative, di debug, utilita generiche di basso livello.
 * Questa classe contiene funzioni informative, di debug, utilita generiche di basso livello.
 * <br/>
 * Viene acceduta tramite la variabile globale <strong>{{#crossLink "Utility"}}CISUtil{{/crossLink}}</strong>.
 * Ad esempio per utilizzare la funzione getObjectName, fare così:
 * 
 *    CISUtil.getObjectName(myObj);
 *
 * @class Tabs
 * @constructor
 */
CIS.Tabs = function() {

	this.enableTab = function(idtab) {
		$('#'+idtab+' .cis-tab-linguette > a.cis-tab-a').each(function() {
			var $athis = $(this);
			$athis.off('click').click(function() {
				$('#'+idtab+' .cis-tab-linguette a').removeClass('active');
				$('#'+idtab+' .cis-tab-blocco').removeClass('active').addClass('noactive');
				$athis.addClass('active');
				var iddiv = $athis.attr('data-name');
				$('#'+idtab+' #Tab'+iddiv).removeClass('noactive').addClass('active');
				var conteggio = $('#'+idtab+' #Tab'+iddiv+' .divLista:first').attr('data-count');
				var loadAlways = $('#'+idtab+' #Tab'+iddiv+' .divLista:first').attr('loadAlways');				
				if ( !conteggio || conteggio == 0 || (loadAlways && loadAlways=='true')) {
					$('#'+idtab+' #Tab'+iddiv).find('.titolorelaz > .refresh_btn').click();
				}				
				AppGlob.resize2(window);
			});
		});
		$('#'+idtab+' .cis-tab-blocco').each(function() {
			//if ( !$(this).hasClass('active'))
//				$(this).hide();
		});
	};
	
}

var cistabs = new CIS.Tabs();
