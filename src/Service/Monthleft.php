<?php
namespace App\Service;

use App\Entity\Idmonthbm;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Repository\CoutRepository;
use App\Repository\InfobilanRepository;

class Monthleft
{
    public function coutprojet(Projet $projet){
        $coutt=0;
        foreach ($projet->getCouts() as $co){
            $coutt=$coutt+ ($co->getNombreprofil()*$co->getProfil()->getTarif());
        }
        return $coutt;
    }

    public function manymonthleft ( Projet $project, Idmonthbm $idmonthbm) {
        $phaseprojet = $project->getPhase()->getId();
        $dateactuelle=$idmonthbm->getMonthyear();
        $mymonthone=date_format($dateactuelle, 'm');
        if ($phaseprojet == 6) {
            if ($project->getDate1()!=null){
                $mois=date_diff($project->getDate1(),$dateactuelle)->format('%m');
                if ($mois <= 0) {
                    $mois=1;
                }
                else{
                    $mois=$mois+1;
                }
            }
            else{
                $mois=1;
            }
        } elseif ($phaseprojet == 7) {
            if ($project->getDate2() != null) {
                $mois=date_diff($project->getDate2(),$dateactuelle)->format('%m');
                if ($mois <= 0) {
                    $mois=1;
                }
                else{
                    $mois=$mois+1;
                }
            }
            else{
                $mois=1;
            }
        }
        else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
            if ($project->getDate3() != null) {
                $mois=date_diff($project->getDate3(),$dateactuelle)->format('%m');
                if ($mois <= 0) {
                    $mois=1;
                }
                else{
                    $mois=$mois+1;
                }
            }
            else{
                $mois=1;
            }
        }
        else{
            $mois=1;
        }
        return $mois;
    }


    public function proposeTGIM(InfobilanRepository $infobilanRepository, CoutRepository $coutRepository, Idmonthbm $idmonthbm,Profil $profil, Projet $projet,$mois){
        $phaseprojet = $projet->getPhase()->getId();
        if ($phaseprojet == 6) {  //conception
            $pourcentagecontrol = $projet->getDebit1bm();
        } elseif ($phaseprojet == 7) { //construction
            $pourcentagecontrol = $projet->getDebit1bm() + $projet->getDebit2bm();
        } else if ($phaseprojet == 8) // test
        {
            $pourcentagecontrol = $projet->getDebit1bm() +$projet->getDebit2bm() + $projet->getDebit3bm();
        } else {
            $pourcentagecontrol = $projet->getDebit1bm() + $projet->getDebit2bm()+ $projet->getDebit3bm()+$projet->getDebit4bm();
        }



        $coutt=0;
        foreach ($projet->getCouts() as $co){
            $coutt=$coutt+ ($co->getNombreprofil()*$co->getProfil()->getTarif());
        }
        $bilansdebites=$infobilanRepository->searchinfobilandebiteduprofitfalse($projet->getId(),$profil->getId());
        $nbdebites=0;
        if (sizeof($bilansdebites,COUNT_NORMAL)==0){
            $nbdebites=0;
        }
        else {
            foreach ($bilansdebites as $d) {
                $nbdebites = $nbdebites + (($d->getNombreprofit()));
            }
        }
        $nbt=$coutRepository->findOneBy(array('projet'=>$projet->getId(),'profil'=>$profil->getId()))->getNombreprofil();
        $l=($pourcentagecontrol/100)*$nbt;
        $l=$l-$nbdebites;
        $l=intdiv($l,$mois);
        if($nbt-$nbdebites-$l<=0){
            $l=0;
        }
        if ($l<0){
            $l=0;
        }

        return $l;




    }



    public function whichpoc(Projet $project){
        $phaseprojet = $project->getPhase()->getId();
        if ($phaseprojet == 6) {  //conception
            $pourcentagecontrol = $project->getDebit1bm();
        } elseif ($phaseprojet == 7) { //construction
            $pourcentagecontrol = $project->getDebit1bm() + $project->getDebit2bm();
        } else if ($phaseprojet == 8) // test
        {
            $pourcentagecontrol = $project->getDebit1bm() +$project->getDebit2bm() + $project->getDebit3bm();
        } else {
            $pourcentagecontrol = $project->getDebit1bm() + $project->getDebit2bm()+ $project->getDebit3bm()+$project->getDebit4bm();
        }

        return $pourcentagecontrol;
    }

}

