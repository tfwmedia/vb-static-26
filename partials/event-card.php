<?php
declare(strict_types=1);

/**
 * Reusable event-card partial.
 *
 * Expected $event shape:
 *   [
 *     'title'       => string,
 *     'subtitle'    => string,
 *     'date'        => 'YYYY-MM-DD',
 *     'time'        => 'HH:MM' (optional, default '18:00'),
 *     'venue'       => string (optional),
 *     'image'       => string (asset URL, optional),
 *     'image_alt'   => string (optional),
 *     'href'        => string (link target for the card),
 *     'ticket_url'  => string (optional; overrides href for the CTA),
 *     'price'       => string (optional),
 *     'price_note'  => string (optional),
 *     'status'      => 'tickets'|'few_left'|'premiere'|'sold_out'|'archive',
 *     'eyebrow'     => string (optional),
 *   ]
 *
 * @var array<string, mixed> $event
 * @var bool $compact  When true, renders a denser card variant.
 */

$eventTitle       = $event['title'] ?? '';
$eventSubtitle    = $event['subtitle'] ?? '';
$eventDate        = $event['date'] ?? '';
$eventTime        = $event['time'] ?? '18:00';
$eventVenue       = $event['venue'] ?? null;
$eventImage       = $event['image'] ?? '';
$eventImageAlt    = $event['image_alt'] ?? $eventTitle;
$eventHref        = $event['href'] ?? ($event['ticket_url'] ?? '#');
$eventTicketUrl   = $event['ticket_url'] ?? $eventHref;
$eventPrice       = $event['price'] ?? null;
$eventPriceNote   = $event['price_note'] ?? null;
$eventStatus      = $event['status'] ?? 'tickets';
$eventEyebrow     = $event['eyebrow'] ?? null;
$compact          = $compact ?? false;
$dayLabel         = $eventDate !== '' ? weekday_full_de($eventDate) : '';
$dateTs           = $eventDate !== '' ? strtotime($eventDate) : false;
$isoDateTime      = $eventDate !== '' ? iso_local($eventDate, $eventTime) : '';
?>
<article class="event-card<?= $compact ? ' event-card--compact' : '' ?>"
         itemscope
         itemtype="https://schema.org/Event">
    <meta itemprop="startDate" content="<?= e($isoDateTime) ?>">
    <meta itemprop="eventStatus" content="<?= e(event_status_url($eventStatus)) ?>">
    <meta itemprop="eventAttendanceMode" content="https://schema.org/OfflineEventAttendanceMode">

    <a class="event-card__media"
       href="<?= e($eventHref) ?>"
       itemprop="url"
       <?= str_starts_with((string) $eventHref, 'http') ? 'rel="noopener"' : '' ?>>
        <?php if ($eventImage !== '') : ?>
            <img src="<?= e($eventImage) ?>"
                 alt="<?= e($eventImageAlt) ?>"
                 width="450" height="300"
                 loading="lazy" decoding="async"
                 itemprop="image">
        <?php else : ?>
            <span class="gallery__placeholder" aria-hidden="true">Bild folgt</span>
        <?php endif; ?>
        <?php if ($dateTs !== false) : ?>
            <?= render_date_badge($eventDate, $eventTime) ?>
        <?php endif; ?>
        <span class="event-card__chip-row">
            <?= render_status_chip($eventStatus) ?>
        </span>
    </a>

    <div class="event-card__body">
        <?php if ($eventEyebrow) : ?>
            <p class="eyebrow" style="margin-bottom: 0;"><?= e($eventEyebrow) ?></p>
        <?php endif; ?>
        <h3 class="event-card__title" itemprop="name"><?= e($eventTitle) ?></h3>
        <?php if ($eventSubtitle !== '') : ?>
            <p class="event-card__subtitle"><?= e($eventSubtitle) ?></p>
        <?php endif; ?>
        <div class="event-card__meta">
            <?php if ($dateTs !== false) : ?>
                <span class="event-card__meta-item">
                    <time datetime="<?= e($eventDate) ?>">
                        <?= e($dayLabel) ?>, <?= e(date('d', $dateTs)) ?>. <?= e(month_short_de($eventDate)) ?>
                    </time>
                    · <?= e($eventTime) ?> Uhr
                </span>
            <?php endif; ?>
            <?php if (!empty($eventVenue)) : ?>
                <span class="event-card__meta-item">📍 <?= e($eventVenue) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <?php
        // Footer shows a price label + CTA. For marketing/hero cards where
        // the "price" field carries premiere metadata instead of a real
        // price, we detect that and render as a tagline instead of "€X".
        // Buttons always read "Tickets kaufen"; the price stays invisible
        // as schema.org Offer meta.
        $hasRealPrice = is_numeric(str_replace(',', '.', preg_replace('/[^0-9,.]/', '', (string) $eventPrice))) && str_contains((string) $eventPrice, '€');
        $priceLabel = 'Tickets kaufen';
    ?>
    <?php if ($eventPrice !== null || !empty($eventTicketUrl)) : ?>
        <div class="event-card__footer">
            <?php if ($eventPrice !== null) : ?>
                <span class="event-card__price">
                    <?= e($eventPrice) ?>
                    <?php if ($eventPriceNote) : ?>
                        <small><?= e($eventPriceNote) ?></small>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($eventTicketUrl)) : ?>
                <a class="btn btn-primary btn-sm"
                   href="<?= e($eventTicketUrl) ?>"
                   rel="noopener"
                   itemprop="offers"
                   itemscope
                   itemtype="https://schema.org/Offer">
                    <meta itemprop="url" content="<?= e($eventTicketUrl) ?>">
                    <meta itemprop="availability" content="https://schema.org/InStock">
                    <meta itemprop="priceCurrency" content="EUR">
                    <?php if ($hasRealPrice) : ?>
                        <meta itemprop="price" content="<?= e((string) preg_replace('/[^0-9,.]/', '', (string) $eventPrice)) ?>">
                    <?php endif; ?>
                    <?= e($priceLabel) ?>
                    <svg class="btn__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</article>