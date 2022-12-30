<h1>Tickets</h1>

<?php foreach ($data['tickets'] as $ticket) { ?>
  <article>
    <h2><?= htmlspecialchars_decode($ticket->getTitle()) ?></h2>

    <a href="?page=ticket_show&id=<?= $ticket->getId() ?>" role="button">Détails</a>
  </article>
<?php } ?>