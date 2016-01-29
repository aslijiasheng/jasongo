package model

import (
	"time"
)

type DdAction struct {
	ActId                int       `json:"act_id" xorm:"not null pk autoincr INT(11)"`
	ActName              string    `json:"act_name" xorm:"CHAR(255)"`
	ActDescription       string    `json:"act_description" xorm:"CHAR(255)"`
	ObjId                int       `json:"obj_id" xorm:"INT(11)"`
	CreateTime           time.Time `json:"create_time" xorm:"DATETIME"`
	ControllerTemplateId int       `json:"controller_template_id" xorm:"INT(11)"`
	ViewTemplateId       int       `json:"view_template_id" xorm:"INT(11)"`
}
