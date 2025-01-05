<?php

namespace backend\modules\api\controllers;

use common\models\Invoice;
use yii\rest\ActiveController;

class InvoiceController extends ActiveController
{
    public $modelClass = 'common\models\Invoice';
    public function actionInvoices($id){
        $invoices = Invoice::getInvoicesForUser($id);
        if($invoices) {
            \Yii::$app->response->statusCode = 200;
            return $invoices;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Invoices not found'];
    }
    public function actionInvoice($id){
        $invoice = Invoice::getInvoiceById($id);
        if($invoice) {
            \Yii::$app->response->statusCode = 200;
            return $invoice;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Invoice not found'];
    }
}