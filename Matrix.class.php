<?php

class Matrix{
    private $level;
    private $executable = true;

    public function __construct(){
        if(isset($_REQUEST['level'])){
            $this->level = (int)$_REQUEST['level'];
            if(!is_numeric($this->level) or $this->level < 1){
                $this->executable = false;
            } else if($this->level % 2 == 0){
                $this->executable = false;
            } else if($this->level > 100){
                $this->executable = false;
            }
        } else {
            $this->level = 3;
            //$this->executable = false;
        }
    }

    public function display(){
        echo '<h1>Matrix</h1>';
        echo '<form name="main" method="POST">';
        echo 'Level<input type="text" name="level" size="3" value="' . $this->level . '">(����Τߡ�max 99)';
        echo '<input type="hidden" name="before_level" value="' . $this->level . '">';
        echo '<br/>�ꥻ�å�<input type="checkbox" name="reset">';
        echo '<br/>������碌<input type="checkbox" name="answer">';
        echo '<br/>����ɽ��<input type="checkbox" name="display_answer">';
        echo '<br/><input type="submit">';

        if(!$this->executable){
            echo '<h2>invalid parameter</h2>';
            return;
        }
        echo '<p>1����' . ($this->level * $this->level) . '�ޤǤΰۤʤ������ġ������Ф�Υ�������¤��������ʤ�褦�����Ϥ��Ʋ�������';
        if($this->level == 99){
            echo "<p>ɽ�����ʤ��ۤ����ɤ��äƸ��äƤ�Τˡ���ä��㤦�Τ�!";
            echo "<p>�����ɽ��������mixi����𤹤뤳��(�������:������)";
        }
        if($this->level == 77){
            echo "<p>77�ǲ����򤷤����Ȥ�?������ʬ����ޤ���";
            echo "<p>�����ɽ��������mixi����𤹤뤳��(�������:��ä���)";
        }
        $this->displayMatrix($this->level);
        if(isset($_REQUEST['answer'])){
            $result = $this->checkMatrix();
            if(empty($result)){
                echo "<h1>" . $this->completeMessage($this->level) . "</h1>";
            } else {
                echo "<h1>$result</h1>";
            }
        }
        if(isset($_REQUEST['display_answer'])){
            $this->displayAnswerMatrix($this->genMatrix($this->level));
        }
        echo '</form>';
    }

    private function completeMessage($num){
        if($num == 3){
            return "Good Job! �Ǥ�ޤ����θ��Ǥ���! mixi����𤷤Ʋ�����(�������:333)";
        } else if($num == 5){
            return "Good Job! ������Ǧ���ϤǤ���! mixi����𤷤Ʋ�����(�������:�դ��󤬡�)";
        } else if($num > 5){
            return "Good Job! ���ʤ��Ͽ��Ǥ�! mixi����𤷤Ʋ�����(�������:god)";
        }
        return "Good Job!";
    }

    private function displayMatrix($num){
        if(isset($_REQUEST['reset'])){
            $reset = true;
        } else {
            $reset = false;
        }

        $before_level = $_REQUEST['before_level'];
        if($before_level != $this->level or $reset){
            $set_value = false;
        } else {
            $set_value = true;
        }

        echo '<table border="1">';
        for($i = 0;$i < $num;$i++){
            echo '<tr>';
            for($j = 0;$j < $num;$j++){
                if(isset($_REQUEST['cell' . $i . $j]) and $set_value){
                    $value = $_REQUEST['cell' . $i . $j];
                } else {
                    $value = null;
                }
                echo '<td><input type="text" name="cell' . $i . $j . '" size="3" value="' . $value . '"></td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    private function displayAnswerMatrix($theMatrix){
        echo '<table border="1">';
        for($i = 0;$i < $this->level;$i++){
            echo '<tr>';
            for($j = 0;$j < $this->level;$j++){
                echo '<td>' . $theMatrix[$i][$j] . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    private function checkMatrix(){
        $num = $this->level;
        $answer_total = $this->genTotalSum($num);

        $diagonal_l = 0;
        $diagonal_r = 0;

        $duplicate = array();

        for($i = 0; $i < $num; $i++){
            $h_total = 0;
            $v_total = 0;
            for($j = 0; $j < $num; $j++){
                if(!isset($_REQUEST['cell' . $i . $j])){
                    return "false";
                }
                if(!isset($_REQUEST['cell' . $j . $i])){
                    return "false";
                }

                if(isset($duplicate[$_REQUEST['cell' . $i . $j]])){
                    return "false(��ʣ�ͤ�����ޤ�)";
                }
                $duplicate[$_REQUEST['cell' . $i . $j]] = true;

                $h_total += $_REQUEST['cell' . $i . $j];
                $v_total += $_REQUEST['cell' . $j . $i];
            }

            if($h_total != $answer_total){
                return "false(" . ($i + 1) . "����)";
            }
            if($v_total != $answer_total){
                return "false(" . ($j + 1) . "����)";
            }
            $diagonal_l += $_REQUEST['cell' . $i . $i];;
            $diagonal_r += $_REQUEST['cell' . $i . ($num - $i - 1)];
        }
        if($diagonal_l != $answer_total){
            return "false(���Ф��г���)";
        }
        if($diagonal_r != $answer_total){
            return "false(���Ф��г���)";
        }
        return null;
    }

    private function genTotalSum($num){
        $matrix = $this->genMatrix($num);
        $total = 0;
        for($i = 0; $i < $num; $i++){
            $total += $matrix[0][$i];
        }
        return $total;
    }

    private function genMatrix($num){
        $matrix = array();

        $value = 1;
        for($i = 0;$i < $num;$i++){
            for($j = 0;$j < $num;$j++){
                if($i == 0 and $j == 0){
                    $x = (($num + 1) / 2) - 1;
                    $y = 0;
                } else {
                    $nx = $x + 1;
                    $ny = $y - 1;

                    if($ny < 0){
                        $ny = $num - 1;
                    }

                    if($nx > $num - 1){
                        $nx = 0;
                    }

                    if(isset($matrix[$ny][$nx])){
                        $nx = $x;
                        $ny = $y + 1;
                    }

                    $x = $nx;
                    $y = $ny;
                }

                $matrix[$y][$x] = $value;
                $value++;
            }
        }
        return $matrix;
    }

}

?>
