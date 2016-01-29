package model

import (
	"time"
)

type DdTemplate struct {
	TemplateId   int       `json:"template_id" xorm:"not null pk autoincr INT(11)"`
	TemplateName string    `json:"template_name" xorm:"CHAR(255)"`
	TemplatePath string    `json:"template_path" xorm:"CHAR(255)"`
	TemplateType string    `json:"template_type" xorm:"CHAR(255)"`
	CreateTime   time.Time `json:"create_time" xorm:"DATETIME"`
	UpdateTime   time.Time `json:"update_time" xorm:"DATETIME"`
}
