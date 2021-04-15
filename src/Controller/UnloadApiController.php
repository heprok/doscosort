<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use App\Repository\PocketDistanceRepository;
use App\Repository\UnloadRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/unload", name: "api_unload_")]
class UnloadApiController extends AbstractController
{
    // private PeopleRepository $peopleRepository;
    // private UnloadRepository $unloadRepository;

    public function __construct(
        private PeopleRepository $peopleRepository,
        private UnloadRepository $unloadRepository,
        private PocketDistanceRepository $pocketDistanceRepository,
        private BoardRepository $boardRepository,
    ) {
    }

    #[Route('/pocket/{duration}', requirements: ["duration" => "today|currentShift|mountly|weekly"], name: "pocket_for_duration_group_quality_cut")]
    public function unloadPocketGroupByQualityCut(string $duration)
    {
        $todayPeriod = BaseEntity::getPeriodForDay();
        $unloadsGroupByQualityCut = $this->unloadRepository->findGroupByCutQualitiesByPeriod($todayPeriod);
        foreach ($unloadsGroupByQualityCut as $key => $unload) {
            $unloadsGroupByQualityCut[$key]['unloadsPocket'] = $this->unloadRepository->findByCutQuality($todayPeriod, $unload['cut'], $unload['qualities']);
        }
        $unloadsGroupByQualityCut["hydra:member"] = $unloadsGroupByQualityCut;
        return $this->json($unloadsGroupByQualityCut);
    }
}
