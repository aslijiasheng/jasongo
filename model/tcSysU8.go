package model

import (
	"time"
)

type TcSysU8 struct {
	SysU8Id     int       `json:"sys_u8_id" xorm:"not null pk autoincr INT(11)"`
	SysU8Date   time.Time `json:"sys_u8_date" xorm:"DATE"`
	SysU8Data   string    `json:"sys_u8_data" xorm:"TEXT"`
	SysU8Result string    `json:"sys_u8_result" xorm:"VARCHAR(255)"`
}
