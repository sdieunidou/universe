<?php

namespace App\Xtense;

use App\Entity\Server;
use App\Entity\User;
use App\Repository\ServerRepository;
use App\Repository\UserRepository;
use App\Xtense\Exception\XtenseException;
use Symfony\Component\HttpFoundation\Request;

class Xtense
{
    const WARNING = 1;
    const ERROR = 2;
    const NORMAL = 3;
    const SUCCESS = 4;

    private $userRepository;

    private $serverRepository;

    public function __construct(
        UserRepository $userRepository,
        ServerRepository $serverRepository
    ) {
        $this->userRepository = $userRepository;
        $this->serverRepository = $serverRepository;
    }

    public function authenticate(Request $request): User
    {
        if (!$password = $request->get('password', false)) {
            throw new XtenseException('Password not provided');
        }

        $user = $this->userRepository->authenticateUserOfXtense($password);
        if (!$user instanceof User) {
            throw new XtenseException('Invalid user token');
        }

        return $user;
    }

    public function resolveServerOfUser(Request $request): Server
    {
        if (!$universe = $request->get('universe', false)) {
            throw new XtenseException('Universe not provided');
        }

        $tmp = explode('.', str_replace('https://', '', $universe));
        $tmp = explode('-', mb_substr($tmp[0], 1));

        if (empty($tmp[0]) || empty($tmp[1]) || !is_numeric($tmp[0])) {
            throw new XtenseException('Server not recognized');
        }

        $server = $this->serverRepository->getServerOfOgameId((int) $tmp[0], $tmp[1]);
        if (!$server instanceof Server) {
            throw new XtenseException('Server not recognized');
        }

        return $server;
    }

    public function getMicrotime()
    {
        $t = explode(' ', microtime());
        return ((float)$t[1] + (float)$t[0]);
    }

    public function processRequest(Request $request, User $user, Server $server): array
    {
        if (!$type = $request->get('type', false)) {
            throw new XtenseException('Type not provided');
        }

        $returnedData = [
            'status' => self::SUCCESS,
            // calls[warning,error]
            // call_messages
        ];

        switch($type) {
            case 'overview':
                break;

            case 'buildings':
                break;

            case 'resourceSettings':
                break;

            case 'defense':
                break;

            case 'researchs':
                break;

            case 'fleet':
                break;

            case 'system':
                break;

            case 'ranking':
                break;

            case 'rc':
            case 'rc_shared':
                break;

            case 'ally_list':
                break;

            case 'messages':
                break;

            case 'spy':
            case 'spy_shared':
                break;

            case 'ennemy_spy':
                break;

            case 'rc_cdr':
                break;

            case 'expedition':
            case 'expedition_shared':
                break;

            default:
                throw new XtenseException(sprintf('Type "%s" not managed', $type));
        }

        return $returnedData;
    }
}