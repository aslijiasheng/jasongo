package model

import (
	"time"
)

type TcApplyopen struct {
	ApplyopenId         int       `json:"applyopen_id" xorm:"not null pk autoincr INT(11)"`
	ApplyopenCreateUser int       `json:"applyopen_create_user" xorm:"INT(11)"`
	ApplyopenCreateTime time.Time `json:"applyopen_create_time" xorm:"DATETIME"`
	ApplyopenOpenInfo   string    `json:"applyopen_open_info" xorm:"TEXT"`
	ApplyopenReturnInfo string    `json:"applyopen_return_info" xorm:"TEXT"`
	ApplyopenOrderDId   int       `json:"applyopen_order_d_id" xorm:"INT(11)"`
}
