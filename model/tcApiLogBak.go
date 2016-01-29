package model

import (
	"time"
)

type TcApiLogBak struct {
	Id                int       `json:"id" xorm:"not null pk autoincr INT(11)"`
	Res               string    `json:"res" xorm:"VARCHAR(10)"`
	Msg               string    `json:"msg" xorm:"VARCHAR(255)"`
	Atime             time.Time `json:"atime" xorm:"DATETIME"`
	ApiLogAddtime     time.Time `json:"api_log_addtime" xorm:"DATETIME"`
	ApiLogMethod      string    `json:"api_log_method" xorm:"VARCHAR(128)"`
	ApiLogData        string    `json:"api_log_data" xorm:"TEXT"`
	ApiLogStatus      int       `json:"api_log_status" xorm:"INT(11)"`
	ApiLogResult      string    `json:"api_log_result" xorm:"VARCHAR(225)"`
	ApiLogOrderNumber string    `json:"api_log_order_number" xorm:"VARCHAR(128)"`
}
