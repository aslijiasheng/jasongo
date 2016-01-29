package model

import (
	"time"
)

type TcRebate struct {
	RebateId          int       `json:"rebate_id" xorm:"not null pk autoincr INT(11)"`
	RebateOrderId     int       `json:"rebate_order_id" xorm:"INT(11)"`
	RebateCreateUser  int       `json:"rebate_create_user" xorm:"INT(11)"`
	RebateCreateTime  time.Time `json:"rebate_create_time" xorm:"DATETIME"`
	RebateAmount      string    `json:"rebate_amount" xorm:"DECIMAL(20,3)"`
	RebateAccountId   int       `json:"rebate_account_id" xorm:"INT(11)"`
	RebatePayMethod   int       `json:"rebate_pay_method" xorm:"INT(11)"`
	RebatePayInfo     string    `json:"rebate_pay_info" xorm:"TEXT"`
	RebateStatus      int       `json:"rebate_status" xorm:"INT(11)"`
	RebateNote        string    `json:"rebate_note" xorm:"TEXT"`
	RebatePerson      string    `json:"rebate_person" xorm:"VARCHAR(225)"`
	RebateExamineNote string    `json:"rebate_examine_note" xorm:"TEXT"`
	RebateBookId      int       `json:"rebate_book_id" xorm:"INT(11)"`
	RebatePayAccount  int       `json:"rebate_pay_account" xorm:"INT(11)"`
	RebateReviewer    int       `json:"rebate_reviewer" xorm:"INT(11)"`
}
