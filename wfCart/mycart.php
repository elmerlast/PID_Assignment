<?php
include("wfCart/wfcart.php");

class myCart extends wfCart{
    var $deliverFee = 0;
    var $grandTotal = 0;

    function empty_cart(){
        $this -> total = 0;
            $this ->total = 0;
            $this ->deliverFee =0;
            $this ->grandTotal = 0;
            $this ->items = array();
            $this ->itemPrices = array();
            $this ->itemQtys = array();
            $this ->iteminfo = array();
    }//覆寫empty_cart方法多清空運費及商品及運費加總這兩項成員變數。

    function _update_total()
    {
        $this->itemcount = 0;
        $this->total = 0;
        if(sizeof($this->items > 0))
        {
            foreach($this->items as $item){
                $this->total = 
                  $this->total + ($this->itemPrices[$items]);
                $this ->itemcount++;
            }
        }
        if($this ->deliverFee >= 1000){
            $this ->deliverFee = 0;
        }else{
            $this ->deliverFee = 150;
        }
        $this ->grandTotal = $this ->total + $this ->deliverFee;
    }//計算如果購買的總價大於1000，即可豁免運費。否則需支付150元的運費。
}

?>