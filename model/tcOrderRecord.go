package model

import (
	"time"
)

type TcOrderRecord struct {
	OrderRecordId        int       `json:"order_record_id" xorm:"not null pk autoincr INT(11)"`
	CancelOrderId        int       `json:"cancel_order_id" xorm:"INT(11)"`
	CancelCreateTime     time.Time `json:"cancel_create_time" xorm:"DATETIME"`
	OrderRecordOperation int       `json:"order_record_operation" xorm:"INT(11)"`
	CancelRemark         string    `json:"cancel_remark" xorm:"TEXT"`
	CancelUserId         int       `json:"cancel_user_id" xorm:"INT(11)"`
}
