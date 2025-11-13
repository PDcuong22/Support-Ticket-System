<?php

namespace App\Interfaces;

interface LabelRepositoryInterface extends BaseRepositoryInterface {
    public function countAll() : int;
}