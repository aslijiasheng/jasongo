package model

import (
	"time"
)

type DdNextid struct {
	Id       int       `json:"id" xorm:"not null pk autoincr INT(11)"`
	AttrName string    `json:"attr_name" xorm:"not null VARCHAR(128)"`
	Nextid   int       `json:"nextid" xorm:"not null INT(11)"`
	LastTime time.Time `json:"last_time" xorm:"not null DATETIME"`
}
