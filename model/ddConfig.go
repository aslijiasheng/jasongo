package model

import (
	"time"
)

type DdConfig struct {
	ConfigId       int       `json:"config_id" xorm:"not null pk autoincr INT(11)"`
	ConfigDatetime time.Time `json:"config_datetime" xorm:"not null DATETIME"`
	ConfigStatus   int       `json:"config_status" xorm:"not null TINYINT(4)"`
	ConfigDifftime int       `json:"config_difftime" xorm:"not null INT(11)"`
}
