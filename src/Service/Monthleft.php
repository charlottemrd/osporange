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
        if ($phaseprojet == 6) {
            if ($projet->getDebit1bm()!=null){
                $pourcentagecontrol = $projet->getDebit1bm();}
            else{
                $pourcentagecontrol = 20;
            }
        } elseif ($phaseprojet == 7) {
            if ($projet->getDebit2bm()!=null){
                $pourcentagecontrol = $projet->getDebit2bm();}
            else{
                $pourcentagecontrol = 60;
            }
        } else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
            if ($projet->getDebit3bm()!=null){
                $pourcentagecontrol = $projet->getDebit3bm();}
            else{
                $pourcentagecontrol = 80;
            }
        } else {
            $pourcentagecontrol = 100;
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

        return $l;




    }



    public function whichpoc(Projet $project){
        $phaseprojet = $project->getPhase()->getId();
        if ($phaseprojet == 6) {
            if ($project->getDebit1bm()!=null){
                $pourcentagecontrol = $project->getDebit1bm();}
            else{
                $pourcentagecontrol = 20;
            }
        } elseif ($phaseprojet == 7) {
            if ($project->getDebit2bm()!=null){
                $pourcentagecontrol = $project->getDebit2bm();}
            else{
                $pourcentagecontrol = 60;
            }
        } else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
            if ($project->getDebit3bm()!=null){
                $pourcentagecontrol = $project->getDebit3bm();}
            else{
                $pourcentagecontrol = 80;
            }
        } else {
            $pourcentagecontrol = 100;
        }
        return $pourcentagecontrol;
    }

}