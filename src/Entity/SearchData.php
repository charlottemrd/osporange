<?php

namespace App\Entity;

use App\Entity\Phase;
use App\Entity\Risque;

class SearchData{

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $ref = '';

    /**
     * @var Fournisseur[]
     */
    public $fournisseurs = [];

    /**
     * @var string
     */
    public $domain = '';


    /**
     * @var string
     */
    public $sdomain = '';

    /**
     * @var User
     */
    public $user = '';


    /**
     * @var Phase[]
     */
    public $phases = [];

    /**
     * @var Risque[]
     */
    public $risques = [];

    /**
     * @var TypeBU[]
     */
    public $bu = [];

    /**
     * @var Priorite[]
     */
    public $priority = [];













}
