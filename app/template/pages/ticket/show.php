<?php
use App\Entity\User;
use Plugo\Services\Auth\Authenticator;
?>

<h1><?= $data['ticket']->getTitle() ?></h1>

<p><?= $data['ticket']->getContent() ?></p>

<img src="<?= $data['ticket']->getImage() ?>">

<?php
if (isset($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
    /** @var User $userSession */
    $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];
}
?>

<?php if ($userSession->getId() === $data['ticket']->getUser()->getId()): ?>
<a href="?page=ticket_edit&id=<?= $data['ticket']->getId() ?>" role="button" class="secondary"><i class="fa-solid fa-pen-to-square"></i></a>
<a href="#" id="delete" role="button" class="secondary"><i class="fa-solid fa-trash"></i></a>
<?php endif; ?>

<script>
  const deleteButton = document.getElementById('delete');

  deleteButton.addEventListener('click', function(e) {
    e.preventDefault();

    if (confirm("Souhaitez-vous vraiment supprimer ce ticket ?")) {
        location.href='?page=ticket_delete&id=' + <?= $data['ticket']->getId() ?>;
    }
  })
</script>
