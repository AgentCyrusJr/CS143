<!DOCTYPE html>
<html>
<body>

<head><title>  
My Calculator  
</title>  

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Input your expression: <input type="text" name="expr">
  <input type="submit" value= "Calculate">
</form>


<?php
    echo "<h2>Result:</h2>";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // collect value of input field 
	if(!empty($_REQUEST[ 'expr' ])) {
	
    $calc = new Calc($_REQUEST[ 'expr' ]);
	//print_r($calc);
    $resultOfCalc = $calc->resultOfCalc;
    echo "<p2>".$resultOfCalc."</p2>";
	}
}


class Calc {
	protected $inputExpr = "";
	public $resultOfCalc = 0;
	

    protected $m_operator_list = array('end');
    protected $caculate_stack = array();
	
	
    protected $operator_list = array('end', '+', '-', '*', '/');
    protected $eval_priority = array('end' => 0, '+' => 1, '-' => 1, '*' => 2, '/' => 2);

    public function __construct($expr) {
        try{
			$this->inputExpr = $expr;
            $this->calculate();
			//print "123".$this->result;
			//$this->calculate();
			//print "456".$this->result;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    protected function calculate() {
		$result = $tmp = array();
		$empty = true;
        $chars = str_split($this->inputExpr);
		for ($i=0; $i<count($chars); $i++) {
			if ($chars[$i]=='-' && $empty) {
				$tmp[] = $chars[$i];
			}
			elseif(in_array($chars[$i], $this->operator_list)) {
				if(!is_numeric(implode('', $tmp))) {
					throw new Exception("Illegal Input Error");
				}
				array_push($result, implode('', $tmp));
				$result[] = $chars[$i];
				$empty = true;
				$tmp = [];
			}
			else {
				$tmp[] = $chars[$i];
				$empty = false;
			}
		}
		if(!is_numeric(implode('', $tmp))) {
			throw new Exception("Illegal Input Error");
		}
        array_push($result, implode('', $tmp));
		//echo "data:";
		//print_r($result);
		
        foreach ($result as $content) {
			//print "content".$content;
			//print_r($this->caculate_stack);
			
			//print "content".$content." is num".is_numeric($content);
            if (is_numeric($content)) {
				
                array_push($this->caculate_stack,$content);
				//echo "afterPush";
				//print_r($this->caculate_stack);
            } else if (in_array($content, $this->operator_list)) {
                while (count($this->m_operator_list)) {
                    $topOp = end($this->m_operator_list);
                    if ($this->eval_priority[$content] <= $this->eval_priority[$topOp]) {
                        $top = array_pop($this->m_operator_list);
                        $this->caculate_stack[]=$top;
                    } else {
						$this->m_operator_list[] = $content;
                        break;
                    }
                }
            }
        }
		
		//print_r($this->caculate_stack);
        while (!empty($this->m_operator_list)) {
            $top = array_pop($this->m_operator_list);
            if ($top == 'end') {
                break;
            } else {
                $this->caculate_stack[]=$top;
            }
        }
		//print_r($this->m_operator_list);
		//print_r($this->caculate_stack);
		$resultStack = array();
        try{
            foreach ($this->caculate_stack as $content) {
                if (is_numeric($content)) {
                    array_push($resultStack, $content);
                } else if (in_array($content, $this->operator_list)) {
                    $fst = array_pop($resultStack);
                    $sec = array_pop($resultStack);
					//echo $fst.", ".$sec;
                    array_push($resultStack, $this->doCalc($fst, $sec, $content));
                }
            }
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
		//print_r($resultStack);
        // echo end($stack);
        $this->resultOfCalc = end($resultStack);
    }

    protected function doCalc($fst, $sec, $op) {
        switch ($op) {
            case '+':
                return $sec+$fst;
                break;
            case '-':
                return $sec-$fst;
                break;
            case '*':
                return $sec*$fst;
                break;
            case '/':
                if($fst==0)
                {
                    throw new Exception("Division by zero error!");
                }
                return $sec/$fst;
                break;
        }
    }
}
?>

</body>
</html>
