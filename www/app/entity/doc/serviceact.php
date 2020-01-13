<?php

namespace App\Entity\Doc;

use \App\Entity\Entry;
use \App\Helper as H;

/**
 * Класс-сущность  локумент акт  о  выполненных работах
 *
 *
 */
class ServiceAct extends Document {

    public function generateReport() {

        $i = 1;

        $detail = array();
        foreach ($this->detaildata as $value) {
            $detail[] = array("no" => $i++,
                "servicename" => $value['service_name'],
                "desc" => $value['desc'],
                "quantity" => $value['quantity'],
                "price" => H::fa($value['price']),
                "amount" => H::fa($value['quantity'] * $value['price'])
            );
        }

        $header = array('date' => date('d.m.Y', $this->document_date),
            "_detail" => $detail,
            "customer_name" => $this->headerdata["customer_name"],
            "order" => $this->headerdata["order"],
            "gar" => $this->gar,
            "document_number" => $this->document_number,
            "payamount" => H::fa($this->payamount),
            "payed" => H::fa($this->payed),
            "total" => H::fa($this->amount)
        );
        $report = new \App\Report('serviceact.tpl');

        $html = $report->generate($header);

        return $html;
    }

    public function Execute() {
        $conn = \ZDB\DB::getConnect();


        foreach ($this->detaildata as $row) {

            $sc = new Entry($this->document_id, 0 - $row['amount'], 0 - $row['quantity']);
            $sc->setService($row['service_id']);
            $sc->setExtCode($row['amount']); //Для АВС 
            //$sc->setCustomer($this->customer_id);
            $sc->save();
        }



        return true;
    }

    public function updateStatus($state) {

        parent::updateStatus($state);

        if ($state != Document::STATE_EXECUTED && $state != Document::STATE_INPROCESS) {
            return;
        }

        $conn = \ZDB\DB::getConnect();

        //была  ли  оплата
        $cnt = $conn->GetOne("select coalesce(count(*),0) from paylist where document_id={$this->document_id} and indoc=1");

        if ($cnt > 0)
            return;

        
        if ($this->headerdata['payment'] > 0 && $this->payed>0) {
            \App\Entity\Pay::addPayment($this->document_id, 1, $this->payed, $this->headerdata['payment'], \App\Entity\Pay::PAY_BASE_OUTCOME );
           
        }
    }

    protected function getNumberTemplate() {
        return 'АКТ-000000';
    }

}
