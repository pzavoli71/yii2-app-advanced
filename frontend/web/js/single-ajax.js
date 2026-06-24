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

    function closeModal($overlay) {
        $overlay.remove();
        if ($('.single-modal-overlay').length === 0) {
            $('body').removeClass('single-modal-open');
        }
    }

    function openModal(url, data, method) {
        var m = buildModal();
        loadInto(m.body, url, data, method);
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
            openModal(url);
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
    $(document).on('submit', 'form[data-single-form]', function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action') || window.location.href;
        loadInto(targetContainer($form), url, $form.serialize(), 'POST');
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
                closeModal($a.closest('.single-modal-overlay'));
                window.location.reload();
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
        var $rel = $(this).closest('.single-rel');
        var url = $rel.data('reload-url');
        if (!url) {
            return;
        }
        var $body = $rel.find('.single-rel-body');
        $body.html('<div class="single-loading"><i class="fa fa-spinner fa-spin"></i></div>');
        $.ajax({
            url: url,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (html) {
            $body.html(html);
        });
    });

})(jQuery);
