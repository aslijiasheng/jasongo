package model

import (
	"time"
)

type TcSalespay struct {
	SalespayId                     int       `json:"salespay_id" xorm:"not null pk autoincr INT(11)"`
	SalespayOrderId                int       `json:"salespay_order_id" xorm:"INT(11)"`
	SalespayPayDate                time.Time `json:"salespay_pay_date" xorm:"DATETIME"`
	SalespayPayMethod              int       `json:"salespay_pay_method" xorm:"INT(11)"`
	SalespayPayInfo                string    `json:"salespay_pay_info" xorm:"VARCHAR(225)"`
	SalespayStatus                 int       `json:"salespay_status" xorm:"INT(11)"`
	SalespayPayNote                string    `json:"salespay_pay_note" xorm:"VARCHAR(225)"`
	SalespayPayAmount              string    `json:"salespay_pay_amount" xorm:"DECIMAL(20,2)"`
	SalespayCreateUser             int       `json:"salespay_create_user" xorm:"INT(11)"`
	SalespayCreateTime             time.Time `json:"salespay_create_time" xorm:"DATETIME"`
	SalespayFinance                int       `json:"salespay_finance" xorm:"INT(11)"`
	SalespayExamineNote            string    `json:"salespay_examine_note" xorm:"VARCHAR(225)"`
	SalespayMyBankAccount          int       `json:"salespay_my_bank_account" xorm:"INT(11)"`
	SalespayMyAlipayAccount        int       `json:"salespay_my_alipay_account" xorm:"INT(11)"`
	SalespayBookId                 int       `json:"salespay_book_id" xorm:"INT(11)"`
	SalespaySpDate                 time.Time `json:"salespay_sp_date" xorm:"DATETIME"`
	SalespaySpRealDate             time.Time `json:"salespay_sp_real_date" xorm:"DATETIME"`
	SalespayAlipayOrder            string    `json:"salespay_alipay_order" xorm:"VARCHAR(128)"`
	SalespayMoneyType              int       `json:"salespay_money_type" xorm:"INT(11)"`
	SalespayNumber                 string    `json:"salespay_number" xorm:"VARCHAR(128)"`
	SalespayAccountBankAccount     string    `json:"salespay_account_bank_account" xorm:"VARCHAR(128)"`
	SalespayAccountBankAccountName string    `json:"salespay_account_bank_account_name" xorm:"VARCHAR(128)"`
	SalespayAlipayAccount          string    `json:"salespay_alipay_account" xorm:"VARCHAR(128)"`
	SalespayCheckName              string    `json:"salespay_check_name" xorm:"VARCHAR(128)"`
	SalespayAccountBankType        int       `json:"salespay_account_bank_type" xorm:"INT(11)"`
	SalespayAccountBankName        string    `json:"salespay_account_bank_name" xorm:"VARCHAR(128)"`
	SalespayAccountAlipayAccount   string    `json:"salespay_account_alipay_account" xorm:"VARCHAR(128)"`
	SalespayMoneyTypeName          int       `json:"salespay_money_type_name" xorm:"INT(11)"`
	SalespayReviewer               int       `json:"salespay_reviewer" xorm:"INT(11)"`
	SalespayRebateAccount          int       `json:"salespay_rebate_account" xorm:"INT(11)"`
	SalespayRebatePerson           string    `json:"salespay_rebate_person" xorm:"VARCHAR(128)"`
	SalespayRefundProductTxt       string    `json:"salespay_refund_product_txt" xorm:"TEXT"`
	SalespayRefundProductJson      string    `json:"salespay_refund_product_json" xorm:"TEXT"`
	SalespaySubsidiary             int       `json:"salespay_subsidiary" xorm:"INT(11)"`
}
