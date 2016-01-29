package model

import (
	"time"
)

type TcApiLog struct {
	ApiLogId          int       `json:"api_log_id" xorm:"not null pk autoincr INT(11)"`
	ApiLogAddtime     time.Time `json:"api_log_addtime" xorm:"DATETIME"`
	ApiLogMethod      string    `json:"api_log_method" xorm:"VARCHAR(128)"`
	ApiLogData        string    `json:"api_log_data" xorm:"TEXT"`
	ApiLogStatus      int       `json:"api_log_status" xorm:"INT(11)"`
	ApiLogResult      string    `json:"api_log_result" xorm:"VARCHAR(225)"`
	ApiLogOrderNumber string    `json:"api_log_order_number" xorm:"VARCHAR(128)"`
}
