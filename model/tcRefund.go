package model

import (
	"time"
)

type TcRefund struct {
	RefundId         int       `json:"refund_id" xorm:"not null pk autoincr INT(11)"`
	RefundManage     int       `json:"refund_manage" xorm:"INT(11)"`
	RefundGoodsInfo  string    `json:"refund_goods_info" xorm:"TEXT"`
	RefundPayMethod  int       `json:"refund_pay_method" xorm:"INT(11)"`
	RefundReason     string    `json:"refund_reason" xorm:"TEXT"`
	RefundStatus     int       `json:"refund_status" xorm:"INT(11)"`
	RefundCreateUser int       `json:"refund_create_user" xorm:"INT(11)"`
	RefundCreateTime time.Time `json:"refund_create_time" xorm:"DATETIME"`
	RefundPayInfo    string    `json:"refund_pay_info" xorm:"TEXT"`
	RefundOrderId    int       `json:"refund_order_id" xorm:"INT(11)"`
	RefundAmount     string    `json:"refund_amount" xorm:"DECIMAL(20,3)"`
	RefundBookId     int       `json:"refund_book_id" xorm:"INT(11)"`
	RefundPayAccount int       `json:"refund_pay_account" xorm:"INT(11)"`
	RefundNumber     string    `json:"refund_number" xorm:"VARCHAR(128)"`
	RefundReviewer   int       `json:"refund_reviewer" xorm:"INT(11)"`
}
