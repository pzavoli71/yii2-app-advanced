/*
 * single-ajax.js
 * Apertura delle maschere "Single" come FINESTRA MODALE centrata nella pagina
 * corrente, in ajax, senza iframe. Delega gli eventi su document, quindi
 * funziona anche sul contenuto iniettato dinamicamente. Le modali sono
 * impilabili: aprendo una relazione da dentro una Single, la nuova modale si
 * sovrappone a quella sottostante.
 *
 * Convenzioni (markup generato dai fogli XSLT in generatori/):
 *   a.single-open[data-url]      -> apre la Single (view/create/update) in una
 *                                   nuova modale centrata.
 *   form[data-single-form]       -> submit in ajax; la risposta sostituisce il
 *                                   contenuto della modale corrente.
 *   a.single-delete[data-url]    -> delete in ajax (POST + CSRF); JSON di
 *                                   ritorno {success, message}.
 *   .single-cancel               -> chiude la modale corrente.
 *   .single-tab[data-tab]        -> tab della Single.
 *   .single-rel[data-reload-url] -> blocco relazione ricaricabile.
 *   .single-rel-refresh          -> ricarica il corpo della relazione.
 *
 * Aggiornamento del sottostante alla chiusura della modale:
 *   - modifica  -> ricarica la sola riga d'origine (tr[data-key]) nella lista;
 *   - inserimento / cancellazione -> ricarica l'intera lista (.single-lista);
 *   - se la modale proviene da un blocco relazione (.single-rel) -> aggiorna
 *     quella relazione invece della lista.
 * Richiede: righe lista con data-key e actionLista ajax-aware (renderPartial).
 */
(function ($) {
    'use strict';

    function csrfParam() {
        return $('meta[name="csrf-param"]').attr('content');
    }
    function csrfToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    function showSpinner($container) {
        $container.html('<div class="single-loading"><i class="fa fa-spinner fa-spin"></i> Caricamento...</div>');
    }

    // Carica un frammento via ajax nel contenitore dato (es. corpo modale).
    function loadInto($container, url, data, method) {
        showSpinner($container);
        return $.ajax({
            url: url,
            type: method || 'GET',
            data: data || {},
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (html) {
            $container.html(html);
            $container.trigger('single:loaded');
        }).fail(function (xhr) {
            $container.html('<div class="single-error">Errore: ' + xhr.status + ' ' + xhr.statusText + '</div>');
        });
    }

    // --- Gestione modali ---------------------------------------------------

    // Costruisce una nuova modale e ne restituisce overlay e corpo (.single-container).
    // Gli stili critici sono applicati INLINE: la modale resta centrata e
    // sovrapposta anche se il foglio single-ajax.css non e' caricato. Lo z-index
    // cresce con la profondita' di impilamento (modali sovrapposte).
    function buildModal() {
        var z = 1050 + $('.single-modal-overlay').length * 10;

        var $overlay = $('<div class="single-modal-overlay"></div>').css({
            position: 'fixed',
            top: 0, left: 0, right: 0, bottom: 0,
            background: 'rgba(0,0,0,.45)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '40px 16px',
            overflowY: 'auto',
            zIndex: z
        });
        var $dialog = $('<div class="single-modal-dialog" role="dialog" aria-modal="true"></div>').css({
            position: 'relative',
            width: '100%',
            maxWidth: '720px',
            margin: 'auto',
            background: '#fff',
            borderRadius: '8px',
            boxShadow: '0 10px 40px rgba(0,0,0,.35)'
        });
        var $close = $('<button type="button" class="single-modal-close" aria-label="Chiudi">&times;</button>').css({
            position: 'absolute',
            top: '8px', right: '12px',
            border: 'none',
            background: 'transparent',
            fontSize: '26px',
            lineHeight: 1,
            color: '#888',
            cursor: 'pointer',
            zIndex: 1
        });
        var $body = $('<div class="single-container single-modal-body"></div>').css({ padding: '20px' });

        $dialog.append($close).append($body);
        $overlay.append($dialog);
        $('body').append($overlay).addClass('single-modal-open');
        return { overlay: $overlay, body: $body };
    }

    // Chiude la modale e, in base all'esito registrato, aggiorna cio' che sta
    // sotto: la riga modificata, l'intera lista, o il blocco relazione d'origine.
    function closeModal($overlay) {
        applyOutcome($overlay);
        $overlay.remove();
        if ($('.single-modal-overlay').length === 0) {
            $('body').removeClass('single-modal-open');
        }
    }

    // url della pagina lista corrente (preserva eventuali filtri in query string).
    function listUrl() {
        return window.location.href;
    }

    // Ricarica via ajax l'intera lista sottostante (inserimento/cancellazione).
    function reloadList() {
        var $list = $('.single-lista').first();
        if (!$list.length) {
            window.location.reload();
            return;
        }
        $.ajax({ url: listUrl(), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .done(function (html) {
                var $new = $('<div>').html(html).find('.single-lista').first();
                if ($new.length) { $list.replaceWith($new); } else { window.location.reload(); }
            })
            .fail(function () { window.location.reload(); });
    }

    // Ricarica via ajax la SOLA riga indicata (modifica), preservando il resto
    // della lista (scroll, altre relazioni espanse).
    function reloadRow($row) {
        var key = $row.data('key');
        if (key === undefined || key === null) {
            reloadList();
            return;
        }
        $.ajax({ url: listUrl(), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .done(function (html) {
                var $new = $('<div>').html(html).find('.single-lista tr[data-key="' + key + '"]').first();
                $row.next('.single-rel-row').remove(); // l'eventuale espansione e' ora obsoleta
                if ($new.length) { $row.replaceWith($new); } else { reloadList(); }
            })
            .fail(function () { reloadList(); });
    }

    // Ricarica il corpo di un blocco relazione (.single-rel) dal suo reload-url.
    function refreshRel($rel) {
        var url = $rel.data('reload-url');
        if (!url) { return; }
        var $body = $rel.find('.single-rel-body').first();
        $body.html('<div class="single-loading"><i class="fa fa-spinner fa-spin"></i></div>');
        $.ajax({ url: url, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .done(function (html) { $body.html(html); });
    }

    // Applica l'esito registrato sulla modale a cio' che sta sotto.
    function applyOutcome($overlay) {
        var outcome = $overlay.data('outcome');
        if (!outcome) { return; }
        var $rel = $overlay.data('sourceRel');
        var $row = $overlay.data('sourceRow');
        if ($rel && $rel.length) { refreshRel($rel); return; }
        if (outcome === 'update' && $row && $row.length) { reloadRow($row); return; }
        reloadList(); // inserimento, o modifica senza riga d'origine nota
    }

    // Apre una modale memorizzando il contesto d'origine (riga lista o relazione)
    // e l'url di apertura (per distinguere create da update al salvataggio).
    function openModal(url, $from) {
        var m = buildModal();
        m.overlay.data('openUrl', url);
        if ($from && $from.length) {
            var $cur = $from.closest('.single-modal-overlay');
            var $rel = $from.closest('.single-rel');
            if ($rel.length) {
                m.overlay.data('sourceRel', $rel);
            } else if ($cur.length) {
                // eredita il contesto dalla modale da cui si apre
                m.overlay.data('sourceRel', $cur.data('sourceRel') || null);
                m.overlay.data('sourceRow', $cur.data('sourceRow') || null);
            } else {
                var $tr = $from.closest('.single-grid tr');
                if ($tr.length) { m.overlay.data('sourceRow', $tr); }
            }
        }
        loadInto(m.body, url);
        return m;
    }

    // Contenitore (corpo modale) in cui opera il nodo cliccato.
    function targetContainer($from) {
        return $from.closest('.single-container');
    }

    // --- Apertura view/create/update in modale -----------------------------
    $(document).on('click', 'a.single-open', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        if (url) {
            openModal(url, $(this));
        }
    });

    // --- Chiusura modale ---------------------------------------------------
    $(document).on('click', '.single-modal-close', function (e) {
        e.preventDefault();
        closeModal($(this).closest('.single-modal-overlay'));
    });

    // Click sul backdrop (fuori dal dialog) chiude la modale.
    $(document).on('click', '.single-modal-overlay', function (e) {
        if (e.target === this) {
            closeModal($(this));
        }
    });

    // Esc chiude la modale in cima alla pila.
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            var $top = $('.single-modal-overlay').last();
            if ($top.length) {
                closeModal($top);
            }
        }
    });

    $(document).on('click', '.single-cancel', function (e) {
        e.preventDefault();
        closeModal($(this).closest('.single-modal-overlay'));
    });

    // --- Submit del form in ajax (resta nella modale corrente) -------------
    // Se la risposta non contiene piu' un form, il salvataggio e' riuscito:
    // registro l'esito (create/update) sulla modale; l'aggiornamento di cio'
    // che sta sotto avverra' alla chiusura della modale.
    $(document).on('submit', 'form[data-single-form]', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $overlay = $form.closest('.single-modal-overlay');
        var $container = targetContainer($form);
        var url = $form.attr('action') || window.location.href;
        var openUrl = ($overlay.data('openUrl') || url) + '';
        var isCreate = /create/i.test(openUrl);

        showSpinner($container);
        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (html) {
            $container.html(html);
            $container.trigger('single:loaded');
            var ancoraForm = $container.find('form[data-single-form]').length > 0;
            if (!ancoraForm) {
                $overlay.data('outcome', isCreate ? 'create' : 'update');
            }
        }).fail(function (xhr) {
            $container.html('<div class="single-error">Errore: ' + xhr.status + ' ' + xhr.statusText + '</div>');
        });
    });

    // --- Delete in ajax ----------------------------------------------------
    $(document).on('click', 'a.single-delete', function (e) {
        e.preventDefault();
        var $a = $(this);
        var url = $a.data('url');
        var confirmMsg = $a.data('confirm');
        if (confirmMsg && !window.confirm(confirmMsg)) {
            return;
        }
        var post = {};
        if (csrfParam()) {
            post[csrfParam()] = csrfToken();
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: post,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (res) {
            if (res && res.success) {
                var $overlay = $a.closest('.single-modal-overlay');
                var $rel = $overlay.data('sourceRel');
                $overlay.removeData('outcome'); // evito doppio aggiornamento in closeModal
                closeModal($overlay);
                if ($rel && $rel.length) { refreshRel($rel); } else { reloadList(); }
            } else {
                window.alert((res && res.message) || 'Cancellazione non riuscita.');
            }
        }).fail(function (xhr) {
            window.alert('Errore: ' + xhr.status + ' ' + xhr.statusText);
        });
    });

    // --- Tab della Single --------------------------------------------------
    $(document).on('click', '.single-tab', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var name = $tab.data('tab');
        var $single = $tab.closest('.single');
        $single.find('> .single-tabs > .single-tab').removeClass('active');
        $tab.addClass('active');
        $single.find('> .single-panel').removeClass('active');
        $single.find('> .single-panel[data-tab="' + name + '"]').addClass('active');
    });

    // --- Toggle "+" : espande/collassa le relazioni sotto una riga ---------
    // Lazy: la prima volta carica via ajax il blocco _relazioni nella sotto-riga;
    // i click successivi mostrano/nascondono senza ricaricare. Ricorsivo: il
    // blocco contiene altre righe con il proprio "+".
    $(document).on('click', '.single-rel-toggle', function (e) {
        e.preventDefault();
        var $a = $(this);
        var url = $a.data('url');
        if (!url) {
            return;
        }
        var $row = $a.closest('tr');
        var $next = $row.next('.single-rel-row');

        if ($next.length) {
            $next.toggle();
            swapToggleIcon($a, $next.is(':visible'));
            return;
        }

        var cols = $row.children().length || 100;
        var $relRow = $('<tr class="single-rel-row"><td colspan="' + cols + '"><div class="single-rel-expand"></div></td></tr>');
        $row.after($relRow);
        swapToggleIcon($a, true);
        loadInto($relRow.find('.single-rel-expand'), url);
    });

    function swapToggleIcon($a, expanded) {
        var $i = $a.find('i');
        if (expanded) {
            $i.removeClass('fa-plus-square').addClass('fa-minus-square');
        } else {
            $i.removeClass('fa-minus-square').addClass('fa-plus-square');
        }
    }

    // --- Refresh del corpo di una relazione --------------------------------
    $(document).on('click', '.single-rel-refresh', function (e) {
        e.preventDefault();
        refreshRel($(this).closest('.single-rel'));
    });

})(jQuery);
