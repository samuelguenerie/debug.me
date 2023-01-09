<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\CommentScore;
use App\Entity\Ticket;
use App\Entity\User;
use App\Manager\CommentManager;
use App\Manager\CommentScoreManager;
use App\Manager\TicketManager;
use App\Manager\UserManager;
use Exception;
use Plugo\Controller\AbstractController;
use Plugo\Services\Auth\Authenticator;
use Plugo\Services\Upload\Upload;
use ReflectionException;

class TicketController extends AbstractController {
    /**
     * @return string
     * @throws ReflectionException
     */
    public function index(): string
    {
        $ticketManager = new TicketManager();

        if (!empty($_POST)) {
            if (!empty($_POST['search'])) {
                $search = '%' . $_POST['search'] . '%';

                $tickets = $ticketManager->search(['is_open' => 1], $search, ['created_at' => 'DESC']);
            }
        } else {
            $tickets = $ticketManager->findBy(['is_open' => 1], ['created_at' => 'DESC']);
        }

        return $this->renderView('ticket/index.php', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function show(): string
    {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
            $ticketManager = new TicketManager();
            /** @var Ticket $ticket */
            $ticket = $ticketManager->find($id);

            if (!$ticket) {
                throw new Exception("Ticket with id $id not found.");
            } elseif (!$ticket->getIsOpen()) {
                throw new Exception("Ticket closed.");
            }

            return $this->renderView('ticket/show.php', [
                'title' => $ticket->getTitle(),
                'ticket' => $ticket
            ]);
        }

        throw new Exception('Parameter id required in url.');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function add(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_POST)) {
                    $ticketManager = new TicketManager();
                    $ticket = new Ticket();

                    $ticket->setTitle($_POST['title']);
                    $ticket->setContent($_POST['content']);

                    if (!empty($_FILES['file']['name'])) {
                        $upload = new Upload();

                        if ($uploadedFile = $upload->add()) {
                            $ticket->setImage($uploadedFile);
                        }
                    }

                    $userManager = new UserManager();
                    /** @var User $user */
                    $user = $userManager->find($userSession->getId());

                    $ticket->setUser($user);

                    if ($ticketManager->add($ticket)) {
                        $user->incrementPoint();

                        if ($userManager->edit($user)) {
                            return $this->redirectToRoute('ticket_index');
                        }

                        throw new Exception('An error occurred with the user edit.');
                    }

                    throw new Exception('An error occurred with the ticket add.');
                }

                return $this->renderView('ticket/add.php');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function edit(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser()->getId() !== $userSession->getId()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of ticket with id $id.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    if (!empty($_POST)) {
                        $ticket->setTitle($_POST['title']);
                        $ticket->setContent($_POST['content']);

                        if (!empty($_FILES['file']['name'])) {
                            $upload = new Upload();

                            if ($upload->remove($ticket->getImage())) {
                                if ($uploadedFile = $upload->add()) {
                                    $ticket->setImage($uploadedFile);
                                }
                            }
                        }

                        $ticketManager->edit($ticket);

                        return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                    }

                    return $this->renderView('ticket/edit.php', [
                        'title' => $ticket->getTitle() . ' (édition)',
                        'ticket' => $ticket
                    ]);
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function open(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser()->getId() !== $userSession->getId()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of ticket with id $id.");
                    } elseif ($ticket->getIsOpen()) {
                        throw new Exception("Ticket already open.");
                    }

                    $ticket->setIsOpen(true);

                    $ticketManager->edit($ticket);

                    return $this->redirectToRoute('ticket_index');
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function close(): ?string
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser()->getId() !== $userSession->getId() && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of ticket with id $id.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket already close.");
                    }

                    $ticket->setIsOpen(false);

                    $ticketManager->edit($ticket);

                    return $this->redirectToRoute('ticket_index');
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function delete(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser()->getId() !== $userSession->getId() && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of ticket with id $id.");
                    }

                    if (!empty($ticket->getImage())) {
                        $upload = new Upload();

                        if (!$upload->remove($ticket->getImage())) {
                            throw new Exception("File " . $ticket->getImage() . " of ticket ticket with id $id can't be deleted.");
                        }
                    }

                    $ticketManager->remove($ticket);

                    return $this->redirectToRoute('ticket_index');
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function commentAdd(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $ticketId = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    if (!empty($_POST)) {
                        $commentManager = new CommentManager();
                        $comment = new Comment();

                        $comment->setContent($_POST['content']);

                        $userManager = new UserManager();
                        /** @var User $user */
                        $user = $userManager->find($userSession->getId());

                        $comment->setUser($user);
                        $comment->setTicket($ticket);

                        $commentManager->add($comment);

                        $user->incrementPoint();

                        $userManager->edit($user);

                        return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                    }

                    throw new Exception('Post data required.');
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function commentEdit(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $commentId = $_GET['id'];
                    $commentManager = new CommentManager();
                    /** @var Comment $comment */
                    $comment = $commentManager->find($commentId);

                    if (!$comment) {
                        throw new Exception("Comment with id $commentId not found.");
                    } elseif ($comment->getUser() !== $userSession) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of comment with id $commentId.");
                    }

                    $ticketId = $comment->getTicket()->getId();
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    if (!empty($_POST)) {
                        $comment->setContent($_POST['content']);

                        $commentManager->edit($comment);

                        return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                    }

                    throw new Exception('Post data required.');
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function commentDelete(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $commentId = $_GET['id'];
                    $commentManager = new CommentManager();
                    /** @var Comment $comment */
                    $comment = $commentManager->find($commentId);

                    if (!$comment) {
                        throw new Exception("Comment with id $commentId not found.");
                    } elseif ($comment->getUser() !== $userSession && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn't the author of comment with id $commentId.");
                    }

                    $ticketId = $comment->getTicket()->getId();
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception('Ticket closed.');
                    }

                    $commentManager->remove($comment);

                    return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                }

                throw new Exception('Parameter id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function commentScoreIncrement(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $commentId = $_GET['id'];
                    $commentManager = new CommentManager();
                    /** @var Comment $comment */
                    $comment = $commentManager->find($commentId);

                    if (!$comment) {
                        throw new Exception("Comment with id $commentId not found.");
                    }

                    $ticketId = $comment->getTicket()->getId();
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    $commentScoreManager = new CommentScoreManager();
                    /** @var CommentScore $commentScore */
                    $commentScore = $commentScoreManager->findOneBy(['comment_id' => $commentId, 'user_id' => $userSession->getId()]);

                    if (empty($commentScore)) {
                        $commentScore = new CommentScore();

                        $commentScore->setComment($comment);
                        $commentScore->setUser($userSession);
                        $commentScore->setScore(1);
                        $commentScoreManager->add($commentScore);
                    } elseif ($commentScore->getScore() <= 0) {
                        $commentScore->setScore(1);
                        $commentScoreManager->edit($commentScore);
                    }

                    return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                }

                throw new Exception('Parameter id and comment_id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }

    /**
     * @return null
     * @throws Exception
     */
    public function commentScoreDecrement(): null
    {
        if (!empty($_SESSION[Authenticator::AUTHENTICATOR_USER])) {
            /** @var User $userSession */
            $userSession = $_SESSION[Authenticator::AUTHENTICATOR_USER];

            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $commentId = $_GET['id'];
                    $commentManager = new CommentManager();
                    /** @var Comment $comment */
                    $comment = $commentManager->find($commentId);

                    if (!$comment) {
                        throw new Exception("Comment with id $commentId not found.");
                    }

                    $ticketId = $comment->getTicket()->getId();
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    $commentScoreManager = new CommentScoreManager();
                    /** @var CommentScore $commentScore */
                    $commentScore = $commentScoreManager->findOneBy(['comment_id' => $commentId, 'user_id' => $userSession->getId()]);

                    if (empty($commentScore)) {
                        $commentScore = new CommentScore();

                        $commentScore->setComment($comment);
                        $commentScore->setUser($userSession);
                        $commentScore->setScore(0);
                        $commentScoreManager->add($commentScore);
                    } elseif ($commentScore->getScore() >= 0) {
                        $commentScore->setScore(0);
                        $commentScoreManager->edit($commentScore);
                    }

                    return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                }

                throw new Exception('Parameter id and comment_id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }
}
