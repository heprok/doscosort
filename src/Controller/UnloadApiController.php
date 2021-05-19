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


    #[Route('/balancepockets', name: "balance_pockets")]
    public function balancePocket()
    {
        $result = [];
        $todayPeriod = BaseEntity::getPeriodForDay();

        $countPocket = $this->pocketDistanceRepository->count([]);
        for ($i = 1; $i <= $countPocket; $i++) {
            $sqlWhere = json_decode(/* $request->query->get('sqlWhere') ?? */
                '[{"id":"pocket","nameTable":"u.","logicalOperator":"AND","operator":"=","value":"'
                    . $i . '"}]'
            );
            $row = [
                'pocket' => $i,
                'name_species' => [],
                'quality_1_name' => [],
                'cut' => [],
                'length' => [],
                'count_boards' => 0,
                'volume_boards' => 0.0
            ];  

            $unload = $this->unloadRepository->findLastUnload($todayPeriod, $sqlWhere);
            if ($unload) {
                $period = new DatePeriod($unload->getDrec(), new DateInterval('P1D'), $todayPeriod->getEndDate());
                $sqlWhere = json_decode(
                    '[{"id":"pocket","nameTable":"b.","logicalOperator":"AND","operator":"=","value":"'
                        . $i . '"}]'
                );
                
                $boardsByQualties = $this->boardRepository->getReportVolumeBoardByPeriod($period, $sqlWhere);
                
                foreach ($boardsByQualties as $boardReport) {
                    $row['name_species'][] = $boardReport['name_species'];
                    $row['quality_1_name'][] = $boardReport['quality_1_name'];
                    $row['length'][] = $boardReport['length'];
                    $row['cut'][] = $boardReport['cut'];
                    $row['count_boards'] += $boardReport['count_board'];
                    $row['volume_boards'] += $boardReport['volume_boards'];
                }
            }
            $row['name_species'] = implode(', ', array_unique($row['name_species']));
            $row['quality_1_name'] = implode(', ', array_unique($row['quality_1_name']));
            $row['length'] = implode(', ', array_unique($row['length']));
            $row['volume_boards'] = round($row['volume_boards'], BaseEntity::PRECISION_FOR_FLOAT);
            $row['cut'] = implode(', ', array_unique($row['cut']));
            $result['hydra:member'][] = $row;
            // if($i == 24)
                // dd($sqlWhere, $todayPeriod, $period, $this->unloadRepository->findLastUnload($todayPeriod, $sqlWhere));
        }

        return $this->json($result);
    }
}
