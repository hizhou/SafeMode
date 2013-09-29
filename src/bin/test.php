<?php
require_once __DIR__ . '/../../vendor/autoload.php';


echo '<pre>';
echo "\r\n-------------\r\n";


$code = '<?php
namespace Foo;
function foo(){
echo "\r\n";
}

namespace Bar;
function bar(){}';

$phpParser = new \PHPParser_Parser(new \PHPSafeMode_EPHPParser_Lexer());
$nodes = $phpParser->parse($code);
var_dump($nodes);

$prettyPrinter = \PHPSafeMode_EPHPParser_Printer::getInstance();
echo $prettyPrinter->prettyPrint($nodes, $code);


echo "\r\n-------------\r\n";
echo $code;

echo "\r\n-------------\r\n";
$prettyPrinter = new \PHPParser_PrettyPrinter_Zend();
echo '<?php ' . $prettyPrinter->prettyPrint($nodes, $code);


echo "\r\n-------------\r\n";
echo '</pre>';

/**

array(16) {
  [0]=>
  array(3) {
    [0]=>
    int(372)
    [1]=>
    string(7) "<?php
"
    [2]=>
    int(1)
  }
  [1]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(2) "  "
    [2]=>
    int(2)
  }
  [2]=>
  array(3) {
    [0]=>
    int(309)
    [1]=>
    string(2) "$a"
    [2]=>
    int(2)
  }
  [3]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(6) "    
"
    [2]=>
    int(2)
  }
  [4]=>
  string(1) "="
  [5]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(22) "        
            "
    [2]=>
    int(3)
  }
  [6]=>
  array(3) {
    [0]=>
    int(315)
    [1]=>
    string(5) ""bbb""
    [2]=>
    int(4)
  }
  [7]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(3) "   "
    [2]=>
    int(4)
  }
  [8]=>
  string(1) ";"
  [9]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(8) "
		
		"
    [2]=>
    int(4)
  }
  [10]=>
  array(3) {
    [0]=>
    int(309)
    [1]=>
    string(2) "$c"
    [2]=>
    int(6)
  }
  [11]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(1) " "
    [2]=>
    int(6)
  }
  [12]=>
  string(1) "="
  [13]=>
  array(3) {
    [0]=>
    int(375)
    [1]=>
    string(1) " "
    [2]=>
    int(6)
  }
  [14]=>
  array(3) {
    [0]=>
    int(305)
    [1]=>
    string(1) "1"
    [2]=>
    int(6)
  }
  [15]=>
  string(1) ";"
}

 */