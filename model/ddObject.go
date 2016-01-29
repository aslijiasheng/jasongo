package model

import (
	"time"
)

type DdObject struct {
	ObjId      int       `json:"obj_id" xorm:"not null pk autoincr INT(11)"`
	ObjUid     int       `json:"obj_uid" xorm:"not null default 0 INT(11)"`
	ObjName    string    `json:"obj_name" xorm:"not null VARCHAR(128)"`
	ObjLabel   string    `json:"obj_label" xorm:"not null VARCHAR(128)"`
	ObjIcon    string    `json:"obj_icon" xorm:"VARCHAR(128)"`
	IsObjType  string    `json:"is_obj_type" xorm:"not null default '0' ENUM('0','1')"`
	IsRefObj   string    `json:"is_ref_obj" xorm:"not null default '0' ENUM('0','1')"`
	IsDetail   string    `json:"is_detail" xorm:"not null default '0' ENUM('0','1')"`
	CreateTime time.Time `json:"create_time" xorm:"not null DATETIME"`
	UpdateTime time.Time `json:"update_time" xorm:"not null DATETIME"`
}
