package model

import (
	"time"
)

type DdTask struct {
	Id        int       `json:"id" xorm:"not null autoincr index INT(11)"`
	Code      string    `json:"code" xorm:"VARCHAR(128)"`
	StartTime time.Time `json:"start_time" xorm:"DATETIME"`
	EndTime   time.Time `json:"end_time" xorm:"DATETIME"`
	Status    string    `json:"status" xorm:"not null default 'false' ENUM('true','false')"`
	LastTime  time.Time `json:"last_time" xorm:"DATETIME"`
}
