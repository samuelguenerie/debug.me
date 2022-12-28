<h1><?= htmlspecialchars_decode($data['ticket']->getTitle()) ?></h1>

<p><?= htmlspecialchars_decode($data['ticket']->getContent()) ?></p>

<a href="?page=ticket_edit&id=<?= $data['ticket']->getId() ?>" role="button" class="secondary"><i class="fa-solid fa-pen-to-square"></i></a>
<a href="#" id="delete" role="button" class="secondary"><i class="fa-solid fa-trash"></i></a>

<script>
  const deleteButton = document.getElementById('delete');

  deleteButton.addEventListener('click', function(e) {
    e.preventDefault();

    if (confirm("Souhaitez-vous vraiment supprimer ce ticket ?")) {
        location.href='?page=ticket_delete&id=' + <?= $data['ticket']->getId() ?>;
    }
  })
</script>