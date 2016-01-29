package model

import (
	"time"
)

type TcProductOparam struct {
	ProductOparamId             int       `json:"product_oparam_id" xorm:"not null pk autoincr INT(11)"`
	ProductOparamProductBasicId int       `json:"product_oparam_product_basic_id" xorm:"INT(11)"`
	ProductOparamName           string    `json:"product_oparam_name" xorm:"VARCHAR(50)"`
	ProductOparamContent        string    `json:"product_oparam_content" xorm:"TEXT"`
	ProductOparamType           int       `json:"product_oparam_type" xorm:"INT(11)"`
	ProductOparamAtime          time.Time `json:"product_oparam_atime" xorm:"DATETIME"`
	ProductOparamUtime          time.Time `json:"product_oparam_utime" xorm:"DATETIME"`
}
