package model

import (
	"time"
)

type TcOrderShift struct {
	OrderShiftId         int       `json:"order_shift_id" xorm:"not null pk autoincr INT(11)"`
	OrderShiftOrderId    int       `json:"order_shift_order_id" xorm:"INT(11)"`
	OrderShiftOldData    string    `json:"order_shift_old_data" xorm:"VARCHAR(225)"`
	OrderShiftNewData    string    `json:"order_shift_new_data" xorm:"VARCHAR(225)"`
	OrderShiftUpdateTime time.Time `json:"order_shift_update_time" xorm:"DATETIME"`
	OrderShiftTitle      string    `json:"order_shift_title" xorm:"VARCHAR(128)"`
}
