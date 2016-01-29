package model

import (
	"time"
)

type TcApiOldLog struct {
	Id    int       `json:"id" xorm:"not null pk autoincr INT(11)"`
	Res   string    `json:"res" xorm:"VARCHAR(10)"`
	Msg   string    `json:"msg" xorm:"VARCHAR(255)"`
	Atime time.Time `json:"atime" xorm:"DATETIME"`
}
