<?php
/** Some (a lot) of this is inspired/copied from https://bitbucket.org/AMcBain/bb-code-parser/src/707350202cd970c8d7a036de901873964eb68c81/bb-code-parser.php?at=master&fileviewer=file-view-default */
class BBCodeReader {
    private $validCodes = array();

    private $openChar = "[";
    private $closeChar = "]";

    private function importCodes() {
        $validCodes = array(
            "b" => new HTMLBoldCode()
        );
    }

    public function isValidKey(&$array, $key) {
        return (array_key_exists($key, $array) && isset($array[$key]));
    }

    public function __construct() {
        $this->importCodes();
    }

    public function format($input, $codes) {
        //Nothing to parse
        if (strrpos($input, $this->openChar) !== true && strrpos($input, $this->closeChar) !== true) {
            return $input;
        }
        $queue = array();
        $stack = array();
        $tokenizer = new BBCodeTokenizer($input);
        while ($tokenizer->hasNext($this->openChar)) {
            $before = $tokenizer->getNext($this->openChar); //Stuff before the [code]
            $code = $tokenizer->getNext($this->closeChar); // inside the [code]

            if ($code === "") continue; //Skip empty
            if ($before !== "") {
                $queue[] = new BBCodeParser_Token(BBCodeParser_Token::$CONTENT, $before);
            }
            if (strpos($code, $this->openChar) !== false) {
                $code = explode($this->openChar, $code);
                $queue[] = new BBCodeParser_Token(BBCodeParser_Token::$CONTENT, $this->openChar . $code[0]);
                $code = $code[1];
            }

            if ($tokenizer->isEmpty() && substr($input, strlen($input) - strlen($this->closeChar)) !== $this->closeChar) {
                $queue[] = new BBCodeParser_Token(BBCodeParser_Token::$CONTENT, $this->openCharChar . $code);
                continue;
            }

            $equals = strrpos($code, "=");
            if ($equals) {
                $codeName = substr($code, 0, $equals);
                $codeArgument = substr($code, $equals + 1);
            } else {
                $codeName = $code;
                $codeArgument = null;
            }

            if (substr($code, 0, 1) == "/") {
                $codeNoSlash = substr($codeName, 1);
                if (!$codeNoSlash) $codeNoSlash = "";

                if ($this->isValidKey($codes, $codeNoSlash) && ($autoClose = $codes[$codeNoSlash]->getAutoCloseCode()) && $this->isValidKey($codes, $autoClose) && in_array($autoClose, $stack) !== false) {
                    $this->array_removeLast($stack, $autoClose);
                    $queue[] = new BBCodeParser_Token(BBCodeParser_Token::$CODE_END, "/" . $autoClose);
                }
            }
        }

    }
    // Removes the last instance of the given value from an array
    private function array_removeLast(&$stack, $match) {

        for($i = count($stack) - 1; $i >= 0; $i--) {

            if($stack[$i] === $match) {
                array_splice($stack, $i, 1);
                return;
            }
        }
    }
}

class BBCodeTokenizer {
    private $input;
    private $strlen;
    private $position;

    public function __construct($input) {
        $this->input = $input;
        $this->strlen = strlen($input);
        $this->position = 0;
    }

    public function isEmpty() {
        return $this->position >= $this->strlen;
    }

    public function hasNext($delimiter = " ") {
        return strpos($this->input, $delimiter, min($this->strlen, $this->position)) !== false;
    }

    public function getNext($delimiter = " ") {
        //Empty string
        if ($this->position >= $this->strlen) {
            return null;
        }
        $index = strpos($this->input, $delimiter, $this->position);
        if ($index === false) {
            $index = $this->strlen;
        }

        $result = substr($this->input, $this->position, $index - $this->position);
        $this->position = $index + strlen($delimiter);

        return ($result !== false) ? $result : "";
    }

    public function reset() {
        $this->position = 0;
    }
}

class BBCodeParser_Token {
    public static $NONE = 'NONE';
    public static $CODE_START = 'CODE_START';
    public static $CODE_END = 'CODE_END';
    public static $CONTENT = 'CONTENT';

    public static $VALID = 'VALID';
    public static $INVALID = 'INVALID';
    public static $NOTALLOWED = 'NOTALLOWED';
    public static $NOIMPLFOUND = 'NOIMPLFOUND';
    public static $UNDETERMINED = 'UNDETERMINED';

    public $type = 'NONE';
    public $status = 'UNDETERMINED';
    public $content = '';
    public $argument = null;
    public $matches = null; // matching start/end code index

    public function __construct($type, $content, $argument=null) {
        $this->type = $type;
        $this->content = $content;
        $this->status = ($this->type === self::$CONTENT)? self::$VALID : self::$UNDETERMINED;
        $this->argument = $argument;
    }

}


