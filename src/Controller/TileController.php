<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tile;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\TileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TileController extends AbstractController
{
    #[Route('/tile', name: 'app_tile')]
    public function index(): Response
    {
        return $this->render('tile/index.html.twig', [
            'controller_name' => 'TileController',
        ]);
    }


    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    #[Route('/addTile/{id}/x{x}-y{y}/{user_id}', name: 'app_add_tile', methods: ['GET', 'POST'])]
    public function new(Request $request, Game $game, User $user, int $x, int $y,  TileRepository $tileRepository): Response
    {
        $tile = new Tile();
        $tile->setGame($game);
        $tile->setOwner($user);
        $tile->setPositionX($x);
        $tile->setPositionY($y);

        $tileRepository->save($tile, true);

        return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
    }
}
