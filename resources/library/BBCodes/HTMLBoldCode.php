<?php

class HTMLBoldCode implements BBCode {

    public function getName() {
        return "Bold";
    }

    public function getCode() {
        return "b";
    }

    public function open() {
        return "<b>";
    }

    public function close() {
        return "</b>";
    }
}