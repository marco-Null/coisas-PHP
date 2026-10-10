<?php

    class fruteira{
        public $fruta1 = "banana";
        public $fruta2 = "kiwi";
        public $fruta3 = "pera";

        public function comer(){
            echo "você comeu um(a) " . $this->fruta1;;
        }
    }
    
        $minhaFruteira = new fruteira();

        $minhaFruteira->comer();

?>