package model

import (
	"time"
)

type TcSystemLog struct {
	SystemLogId        int       `json:"system_log_id" xorm:"not null pk autoincr INT(11)"`
	SystemLogUserId    int       `json:"system_log_user_id" xorm:"not null INT(11)"`
	SystemLogIp        string    `json:"system_log_ip" xorm:"not null VARCHAR(128)"`
	SystemLogParam     string    `json:"system_log_param" xorm:"not null VARCHAR(225)"`
	SystemLogAddtime   time.Time `json:"system_log_addtime" xorm:"not null DATETIME"`
	SystemLogLoginTime time.Time `json:"system_log_login_time" xorm:"DATETIME"`
	SystemLogOperation string    `json:"system_log_operation" xorm:"VARCHAR(128)"`
	SystemLogModule    string    `json:"system_log_module" xorm:"VARCHAR(128)"`
	SystemLogModuleId  int       `json:"system_log_module_id" xorm:"INT(11)"`
	SystemLogNote      string    `json:"system_log_note" xorm:"TEXT"`
}
