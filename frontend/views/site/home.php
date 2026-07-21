<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Gestione obiettivi scolastici';
$isGuest = Yii::$app->user->isGuest;
?>
<div class="os-home">

    <section class="os-hero">
        <span class="os-eyebrow"><i class="fas fa-graduation-cap"></i>&nbsp; Piattaforma didattica</span>
        <h1>Gestione obiettivi scolastici</h1>
        <p class="lead">
            Organizza obiettivi, materie e verifiche in un unico posto. Monitora i
            progressi degli studenti e allena le competenze con i quiz interattivi.
        </p>
        <div class="os-cta">
            <?php if ($isGuest): ?>
                <?= Html::a('<i class="fas fa-sign-in-alt"></i>&nbsp; Accedi', Url::to(['/site/login']), ['class' => 'btn btn-light']) ?>
                <?= Html::a('<i class="fas fa-user-plus"></i>&nbsp; Registrati', Url::to(['/site/signup']), ['class' => 'btn btn-ghost']) ?>
            <?php else: ?>
                <?= Html::a('<i class="fas fa-bullseye"></i>&nbsp; Vai agli obiettivi', Url::to(['/busy/obiettivo/index']), ['class' => 'btn btn-light']) ?>
                <?= Html::a('<i class="fas fa-question-circle"></i>&nbsp; Quiz', Url::to(['/patente/quiz/index']), ['class' => 'btn btn-ghost']) ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="os-features">
        <article class="os-feature-card">
            <div class="os-feature-icon"><i class="fas fa-bullseye"></i></div>
            <h3>Obiettivi</h3>
            <p>Definisci e segui gli obiettivi didattici, con permessi e stato di avanzamento sempre aggiornati.</p>
        </article>
        <article class="os-feature-card">
            <div class="os-feature-icon"><i class="fas fa-book"></i></div>
            <h3>Materie</h3>
            <p>Gestisci le materie associate a ogni utente e organizza i contenuti per disciplina.</p>
        </article>
        <article class="os-feature-card">
            <div class="os-feature-icon"><i class="fas fa-question-circle"></i></div>
            <h3>Quiz</h3>
            <p>Allena le competenze con quiz interattivi e tieni traccia delle domande da rivedere.</p>
        </article>
        <article class="os-feature-card">
            <div class="os-feature-icon"><i class="fas fa-chart-line"></i></div>
            <h3>Prodotti finanziari</h3>
            <p>Consulta e confronta i prodotti finanziari e gli investimenti collegati all'attività.</p>
        </article>
    </section>

</div>
