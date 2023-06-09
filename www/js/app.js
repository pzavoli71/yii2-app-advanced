(function() {

function AppGlob() {
        var formids = 0;
        
	var ajaxrequest = null;
	// Paride, gestione menu contestuale nelle liste e relazioni tra PDC
	var callbackPerMenuContestuale = null;
	var callbackPerAperturaRelazioni = null;
	this.registraFunzioniPerListe = function(fun_GestMenuCont, fun_GestRelazioni) {
		this.callbackPerMenuContestuale = fun_GestMenuCont;
		this.callbackPerAperturaRelazioni = fun_GestRelazioni;
	};

	// Apre una riga sotto quella corrente, per visualizzare le relazioni aperte con altri pdc
	// Attenzione ad usare le vecchie liste con questa funzione vecchia. Nella vecchia infatti veniva cecata la riga della relazione usando l'indice di posizione (deprecato)
	this.apriRigaRelazioni = function(obj, nomepdc) {	
		var $obj = $(obj);
		var id = $obj.attr('pos'); // e' il position della riga
		var spezzanome = nomepdc.split('.');
		var nome = spezzanome[spezzanome.length - 1];
		var $riga = $obj.parent('td').parent('tr');
		var chiave = $riga.attr('chiave');
		var $td = $riga.find('td:first');
		var lineheight = $td.css('line-height');
		var fontsize = $td.css('font-size');
		var classe = $riga.attr('class');
		var $r = $riga.parent().find('#RigaRel'+nome+'_'+id);
		var ca = 'apri';
		if ( $r.find('td:first').hasClass('closed')) {
			$r.find('td:first').removeClass('closed').addClass('open');
			$r.find('td:first > div').show();
			var src = $obj.find('i').removeClass('fa-plus-square').addClass('fa-minus-square');
			if (this.callbackPerAperturaRelazioni)
				this.callbackPerAperturaRelazioni(chiave, nomepdc, $riga, $r);
		} else {
			$r.find('td:first').removeClass('open').addClass('closed');
			$r.find('td:first > div').hide();
			var src = $obj.find('i').removeClass('fa-minus-square').addClass('fa-plus-square');
			ca = 'chiudi';
		}
		setTimeout(function() {AppGlob.resize2(window)}, 500);
	  }
	
	this.showLoading = function() {
		$('#divloading').css('display','flex');
	};

	this.hideLoading = function() {
		$('#divloading').hide();
	}

	this.eseguiComando = function(href, nomecomando, chiave, parametri, richiestaComando, callback) {
            var dati = {}; //parametri;
            if ( typeof richiestaComando == 'function') {
                    if ( !richiestaComando(nomecomando, chiave, dati, href, callback)) {
                            //alert('Comando annullato');
                            return;
                    }
            }            
            if ( ajaxrequest )
                    ajaxrequest.abort();
            this.showLoading();
            var that = this;            
            $.ajax({
                url: href,
                type: "post",
                dataType: "json",
                async: self.asyncRequest,
                data: dati
                , 
                beforeSend: function (jqXHR) {
                    //self.raise('beforechange', []);
                },
                success: function (data, textStatus, jqXHR) {
                    that.hideLoading();
                    if (data.status === "success") {
                    }
                    if ( typeof callback == 'function')
                            callback(nomecomando, chiave, data, href,callback);
                    console.log(data.search);
                },
                complete: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    that.hideLoading();
                    dati = {};
                    dati.error = 'Errore nell\'elaborazione: ' + errorThrown;
                    if ( typeof callback == 'function')
                            callback(nomecomando, chiave, dati, href,callback);
                }           
             });
        };

	this.eseguiComandoConDialog = function(href, nomecomando, chiave, parametri, richiestaComandoConDialog, callback) {
            var that = this;
            var dati = {};
            if ( typeof richiestaComandoConDialog == 'function') {
                    richiestaComandoConDialog(nomecomando, chiave, dati, href, callback, startComando);
                    return;
            }
            function  startComando(nomecomando, chiave, dati, callback) {
                dati['_csrf'] = '<?=Yii::$app->request->getCsrfToken()?>';
                if ( ajaxrequest )
                        ajaxrequest.abort();
                that.showLoading();
                //var that = this;            
                $.ajax({
                     url: href, 
                     type: 'post',
                     cache: false,
                     async: self.asyncRequest,                     
                     data: dati,
                     dataType: 'json'
                     , 
                    beforeSend: function (jqXHR) {
                        //self.raise('beforechange', []);
                    },
                    success: function (data, textStatus, jqXHR) {
                        that.hideLoading();
                        if (data.status === "success") {
                        }
                        if ( typeof callback == 'function')
                                callback(nomecomando, chiave, data, href,callback);
                        console.log(data.search);
                    },
                    complete: function () {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        that.hideLoading();
                        dati = {};
                        dati.error = 'Errore nell\'elaborazione: ' + errorThrown;
                        if ( typeof callback == 'function')
                                callback(nomecomando, chiave, dati, href,callback);
                    }
                });
            }                
	};
	
	// Trova, nel div della pagina il div corrispondente alla relazione sulla quale l'utebnte ha cliccato.
	this.trovaDivRelazione = function(obj) {
		var $odivRelaz = $(obj);
		while ($odivRelaz && $odivRelaz.length > 0) {
			if ( $odivRelaz.hasClass("divRelazione"))
				return $odivRelaz;
			else {
				$odivRelaz = $odivRelaz.parent('');
			}
		}
		return null;
	};

	// Ricarica la relazione chiamando la servlet in modalita solorelaz.
	// il campo $odivrelaz è il div contenitore dell'intera relazione.
	this.reloadRelazione = function(href, nomepdc, nomerelazione, dati,  $odivRelaz, callback) {
		dati['flSoloCaricaRelaz'] = true;
		dati['nomepdc'] = nomepdc;
		dati['nomerelaz'] = nomerelazione;
		var that = this;
		that.showLoading();
		$.ajax({
			url: href,
			cache: false,
			type:'get',
			data: dati,
			dataType: 'html'
		}).done(function( data ) {
			that.hideLoading();
			$odivRelaz.find('.btn_minimax').attr('title','Minimizza').find('i').removeClass('fa-window-maximize').addClass('fa-window-minimize');
			// Mi tengo da parte il div della tabella che sto per rimuovere, devo leggerne ''id, per aggiungerlo alla nuova tabella.
			var $tablista = $odivRelaz.find('.divLista > .tabLista');
			var idtablista = '';
			if ( $tablista.length > 0 ) {
				idtablista = $tablista.attr('id');
			}
		  	$odivRelaz.find('.divLista').remove();
		  	$odivRelaz.find('div.titolorelaz').after(data);
		  	if ( idtablista == undefined || idtablista == '') {
		  		// Se non lo trovo nel vecchio tabLista lo cerco dentro divRelazione e gli cambio nome
		  		idtablista = $odivRelaz.attr('id');
		  		if ( idtablista != undefined && idtablista != null)
		  			idtablista = idtablista.replace(/divRel_/g,'tabLista');
		  	}		  	
		  	if ( idtablista != undefined && idtablista != '') {
		  		$tablista = $odivRelaz.find('.divLista > .tabLista');
		  		$tablista.attr('id',idtablista);
		  	}
		  	
			if ( callback && typeof(callback) == 'function') 
				callback(dati, data);
			AppGlob.resize2(window);			
		});		
	};
	
	this.emettiErrore = function(errore, callback,paramcallback) {
		if ($('#dialogMessaggio').length == 0) {
			$('body').append('<div id="dialogMessaggio"><div></div></div>');
		}
		$('#dialogMessaggio > div').html(errore);
		$('#dialogMessaggio').dialog({
			autoOpen: true,
			modal:true,
			title:'Messaggio',
			position:{ my: 'top', at: 'top+150' },
			show: {
	        	effect: "blind",
	        	duration: 100
	      	},
	      	hide: {
	        	effect: "explode",
	        	duration: 400
	      	},
			buttons: {
	        	Ok: function() {
	          		$( this ).dialog( "close" );
	          		if ( callback && typeof(callback) == 'function') 
	          			callback(paramcallback);	
	        	}
	      	}
	      	}
		);
	}

	this.inizializzaLista = function() {
		// Inizializzo i toggles
		$('.btn_minimax').unbind().click(function() {
			var $btn = $(this);
			$(this).parent().next('.divLista').toggle(0.5,function() {
				if ($btn.find('i').hasClass('fa-window-minimize')) {
					$btn.attr('title','Massimizza');
					$btn.find('i').removeClass('fa-window-minimize').addClass('fa-window-maximize');
				} else {
					$btn.attr('title','Minimizza');
					$btn.find('i').removeClass('fa-window-maximize').addClass('fa-window-minimize');
				}
			});
		});
	};

	// Ripristina la window di stampa alla sua dimensione originale, oppure la elimino dal dom, quando clicco sul bottone chiudi.
	this.ripristinaFrameDopoStampa = function(bottone,idframe, x, y, wtif, htif, position) {
		var $docframe = $('#'+idframe);
		if ( idframe == 'Principale') {
			if ( x != 'auto')
				x = x + 'px';
			if ( y != 'auto')
				y = y + 'px';
			$docframe.css('position',position).css('border','0px').css('box-shadow', '0px').css('top',y).css('left',x).css('height',htif +'px').css('width',wtif+'px').css('box-shadow','0px 0px');
			$docframe.attr('src', '/contriss/vuoto.html');
		} else {
			$docframe.remove();
		}
		$(bottone).remove();
	}

	// Fa il replace in tempo reale del punto con la virgola nei campi money
	this.replacePunto = function(evento) {
	    //var evt = evento || window.event;

	    var evt = (evento) ? evento : window.event;
	    var keyCode = (evt.keyCode) ? evt.keyCode : evt.which;
	    if ( evt.key && evt.key == 'Delete')
	    	return;
	    	if (keyCode == 46) {
			    var tgt = evt.target;
			    if ( tgt == undefined || !evt.preventDefault) {
			    	evt.keyCode=44;
			    	return;
			    }
			    evt.preventDefault(true);
			    evt.stopPropagation(true);
			    // Append or insert replacement character
			    if ('selectionStart' in tgt){
			      if (tgt.selectionStart == tgt.textLength)
			        tgt.value += String.fromCharCode(44);
			      else {
			        if ( tgt.selectionEnd > tgt.selectionStart) {
			          var lastpos = tgt.selectionStart;
			          var startpos = tgt.selectionEnd;
			          tgt.value = tgt.value.substr(0, lastpos) + String.fromCharCode(44) + tgt.value.substr(startpos);
			          tgt.selectionStart = lastpos + 1;
			          tgt.selectionEnd = lastpos + 1;
			        } else {
			          var lastpos = tgt.selectionStart;
			          tgt.value = tgt.value.substr(0, lastpos) + String.fromCharCode(44) + tgt.value.substr(lastpos);
			          tgt.selectionStart = lastpos + 1;
			          tgt.selectionEnd = lastpos + 1;
			        }
			      }
			    }
	      }
	    //}
	  }

	/**
	 * Effettua il resize della window passata e dei suoi contenitori, in base alle dimensioni del documento corrente,
	 * *SOLO SE* è una iframe. Se il
	 * contenitore è una iframe a sua volta, fa il resize anche di esso.
	 * 
	 * @method resize2 
	 * @param wind {Window}
	 *  la window della finestra da ridimensionare
	*/	
	this.resize2 = function(wind) {
		  var frameNode = wind.frameElement;  //nodo DOM di questa iframe
		  if (!frameNode)
			  return;
		  var doc = wind.document;
		  var minHeight = $(doc).outerHeight(true); //$(doc).find('body').outerHeight(true);
		  minWidth = $(doc).width();	  
		  var $divAbsolute = $('.frameSingle',doc);
		  $divAbsolute.each(function(i) {
			 var $this = $(this);
			 var h = $this.position().top + $this.height();
			 if ( h > minHeight)
				 minHeight = h;
		  });
		  $(frameNode).height(minHeight);
		  /*if ( $(frameNode).hasClass('frame-container')) {
			  $(frameNode).width('100%');
		  } else if (! $(frameNode).hasClass('frame-container')) {
			  $(frameNode).width(minWidth);
	 	  }*/
	 	  	  
		  var wPadre = wind.parent;
		  if ( !wPadre || wPadre == wind)
			  return;
		  if (wPadre && wPadre.frameElement) {
			  setTimeout(function() {AppGlob.resize2(wPadre)},400);
		  }
	};	

	this.showLoading = function() {
		$('#divloading').show();
	};

	this.hideLoading = function() {
		$('#divloading').hide();
	};

        // Apre una form con i parametri impostati
        this.apriForm = function(obj, href, callback, windowparam, title = "Inserisci i parametri") {
            Tabs = window['Tabs'] || null;            		
            if (!window.formids || window.formids === null || window.formids == NaN) {
                    window.formids = 0;
            }
            window.formids++;
            if ( windowparam === null || windowparam === 'undefined' || windowparam === '') {
                windowparam = {};
            }
            var width = 500;
            if ( windowparam.width) 
                width = windowparam.width;
            var s = "<div class='ui-dialog form-container' id='form_" + window.formids + "'>";
            s += "<iframe class='frame-form'></iframe>";
            s += "</div>";
            // Cerco tab-container
            $container = Tabs && Tabs !== null && Tabs !== 'undefined'?Tabs.findContainer(obj):null;
            if ($container === null)
                    $('body').append(s);
            else
                    $container.append(s);
            $form = $('#form_'+window.formids);
            $form[0].atag = obj;
            if ( href === '' || href === 'undefined' || href === null) {
                    href = $(obj).attr('href');
            }	
            $form.dialog({
                    appendTo: $container,
                    autoOpen: true,
                    modal:false,
                    title:title,
                    position:{ my: 'top', at: 'top+150', of: window.top },
                            width: width,
                            show: {
                            effect: "blind",
                            duration: 100
                    },
                    hide: {
                            effect: "explode",
                            duration: 400
                    },
                    open: function() {
                            $form.find('iframe.frame-form').attr('src',href);
                    },
                    beforeClose: function() {
                            if ( callback && callback !== 'undefined') {
                                if ( typeof callback === 'string') {
                                    eval(callback);
                                } else if ( typeof callback === 'function' )
                                    callback();
                            }
                            $(this).dialog( "destroy" );		
                            $(this).remove();
                    }
            });
            return false;
        };
        
        this.cambiaTablesScreens = function() {
            $('.tabLista').each(function() {
               $(this).removeClass('tabLista').addClass('tabListas');
            });
        };
    };
    
    
    AppGlob = new AppGlob();
    window['AppGlob'] = AppGlob;
})();	
