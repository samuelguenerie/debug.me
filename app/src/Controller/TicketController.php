<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ticket;
use App\Entity\User;
use App\Manager\CommentManager;
use App\Manager\TicketManager;
use App\Manager\UserManager;
use Exception;
use Plugo\Controller\AbstractController;

class TicketController extends AbstractController {
    /**
     * @return string
     */
    public function index(): string
    {
        $ticketManager = new TicketManager();

        return $this->renderView('ticket/index.php', [
            'tickets' => $ticketManager->findBy([], ['created_at' => 'DESC'])
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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_POST)) {
                    $ticketManager = new TicketManager();
                    $ticket = new Ticket();

                    $ticket->setTitle($_POST['title']);
                    $ticket->setContent($_POST['content']);
                    $ticket->setIsOpen(true);
                    $ticket->setUser($userSession->getId());

                    $ticketManager->add($ticket);

                    $userManager = new UserManager();
                    /** @var User $user */
                    $user = $userManager->find($userSession->getId());

                    $user->incrementPoint();

                    $userManager->edit($user);

                    return $this->redirectToRoute('ticket_index');
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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser() !== $userSession) {
                        throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of ticket with id $id.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception("Ticket closed.");
                    }

                    if (!empty($_POST)) {
                        $ticket->setTitle($_POST['title']);
                        $ticket->setContent($_POST['content']);

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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser() !== $userSession) {
                        throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of ticket with id $id.");
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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser() !== $userSession && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of ticket with id $id.");
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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($id);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $id not found.");
                    } elseif ($ticket->getUser() !== $userSession && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of ticket with id $id.");
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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
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
                        $comment->setUser($userSession->getId());
                        $comment->setTicket($ticket);

                        $commentManager->add($comment);

                        $userManager = new UserManager();
                        /** @var User $user */
                        $user = $userManager->find($userSession->getId());

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
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id']) && !empty($_GET['comment_id'])) {
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
                        $commentId = $_GET['comment_id'];
                        $commentManager = new CommentManager();
                        $comment = $commentManager->find($commentId);

                        if (!$comment) {
                            throw new Exception("Comment with id $commentId not found.");
                        } elseif ($comment->getUser() !== $userSession) {
                            throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of comment with id $commentId.");
                        }

                        $comment->setContent($_POST['content']);

                        $commentManager->edit($comment);

                        return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                    }

                    throw new Exception('Post data required.');
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
    public function commentDelete(): null
    {
        /** @var User $userSession */
        $userSession = null; // TODO: get user object from session / cookie.

        if (!empty($userSession)) {
            if (!$userSession->getIsBlocked()) {
                if (!empty($_GET['id']) && !empty($_GET['comment_id'])) {
                    $ticketId = $_GET['id'];
                    $ticketManager = new TicketManager();
                    /** @var Ticket $ticket */
                    $ticket = $ticketManager->find($ticketId);

                    if (!$ticket) {
                        throw new Exception("Ticket with id $ticketId not found.");
                    } elseif (!$ticket->getIsOpen()) {
                        throw new Exception('Ticket closed.');
                    }

                    $commentId = $_GET['comment_id'];
                    $commentManager = new CommentManager();
                    /** @var Comment $comment */
                    $comment = $commentManager->find($commentId);

                    if (!$comment) {
                        throw new Exception("Comment with id $commentId not found.");
                    } elseif ($comment->getUser() !== $userSession && !$userSession->getIsModerator()) {
                        throw new Exception("User " . $userSession->getUsername() . " isn\'t the author of comment with id $commentId.");
                    }

                    $commentManager->remove($comment);

                    return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
                }

                throw new Exception('Parameter id and comment_id required in url.');
            }

            throw new Exception("User " . $userSession->getUsername() . " is blocked.");
        }

        throw new Exception('User not logged');
    }
}