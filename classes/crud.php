<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 *
 * @author patrick
 */
interface crud {
    public function insert($data);
    public function update($data);
    public function delete();
}
