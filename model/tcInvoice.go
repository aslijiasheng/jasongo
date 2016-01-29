package model

import (
	"time"
)

type TcInvoice struct {
	InvoiceId                 int       `json:"invoice_id" xorm:"not null pk autoincr INT(11)"`
	InvoiceSaleId             int       `json:"invoice_sale_id" xorm:"not null INT(11)"`
	InvoiceOrderId            int       `json:"invoice_order_id" xorm:"not null INT(11)"`
	InvoiceType               int       `json:"invoice_type" xorm:"not null INT(11)"`
	InvoiceInvoiceNo          string    `json:"invoice_invoice_no" xorm:"not null VARCHAR(128)"`
	InvoiceCustomerType       int       `json:"invoice_customer_type" xorm:"not null default 1002 INT(11)"`
	InvoiceTitle              string    `json:"invoice_title" xorm:"not null VARCHAR(128)"`
	InvoiceAddress            string    `json:"invoice_address" xorm:"not null VARCHAR(128)"`
	InvoiceTel                string    `json:"invoice_tel" xorm:"not null VARCHAR(128)"`
	InvoiceBank               string    `json:"invoice_bank" xorm:"not null VARCHAR(128)"`
	InvoiceContent            string    `json:"invoice_content" xorm:"not null TEXT"`
	InvoiceReceiptContent     string    `json:"invoice_receipt_content" xorm:"not null VARCHAR(256)"`
	InvoiceAmount             string    `json:"invoice_amount" xorm:"DECIMAL(20,2)"`
	InvoiceAddtime            time.Time `json:"invoice_addtime" xorm:"not null DATETIME"`
	InvoiceUntime             time.Time `json:"invoice_untime" xorm:"not null DATETIME"`
	InvoiceMemo               string    `json:"invoice_memo" xorm:"not null VARCHAR(225)"`
	InvoiceTaxpayer           string    `json:"invoice_taxpayer" xorm:"not null VARCHAR(128)"`
	TypeId                    int       `json:"type_id" xorm:"not null INT(11)"`
	InvoiceKpType             int       `json:"invoice_kp_type" xorm:"not null INT(11)"`
	InvoiceUninvoiceStatus    int       `json:"invoice_uninvoice_status" xorm:"not null default 1002 INT(4)"`
	InvoiceApplyUninvoiceNote string    `json:"invoice_apply_uninvoice_note" xorm:"not null TEXT"`
	InvoiceProcessStatus      int       `json:"invoice_process_status" xorm:"not null default 1002 INT(11)"`
	InvoiceProcessNote        string    `json:"invoice_process_note" xorm:"not null TEXT"`
	InvoiceProcessTime        time.Time `json:"invoice_process_time" xorm:"not null DATE"`
	InvoiceDoStatus           int       `json:"invoice_do_status" xorm:"not null default 1002 INT(11)"`
	InvoiceDoNote             string    `json:"invoice_do_note" xorm:"not null TEXT"`
	InvoiceDoTime             time.Time `json:"invoice_do_time" xorm:"not null DATETIME"`
	InvoiceCode               string    `json:"invoice_code" xorm:"not null VARCHAR(128)"`
	InvoiceUninvoiceNote      string    `json:"invoice_uninvoice_note" xorm:"not null TEXT"`
	InvoiceApplyUntime        time.Time `json:"invoice_apply_untime" xorm:"not null DATE"`
	InvoiceAccount            string    `json:"invoice_account" xorm:"VARCHAR(225)"`
	InvoiceReviewer           int       `json:"invoice_reviewer" xorm:"INT(11)"`
	InvoiceIsUninvoiceNote    string    `json:"invoice_is_uninvoice_note" xorm:"TEXT"`
	InvoiceNumber             string    `json:"invoice_number" xorm:"VARCHAR(225)"`
	InvoiceStatus             int       `json:"invoice_status" xorm:"INT(11)"`
	InvoiceContentTxt         string    `json:"invoice_content_txt" xorm:"TEXT"`
	InvoiceSubsidiary         int       `json:"invoice_subsidiary" xorm:"INT(11)"`
}
