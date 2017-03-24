<?php

interface BBCode {
    public function getName();
    public function getCode();
    public function open();
    public function close();
    public function getAutoCloseCode();
}