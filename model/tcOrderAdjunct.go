package model

import (
	"time"
)

type TcOrderAdjunct struct {
	OrderAdjunctId      int       `json:"order_adjunct_id" xorm:"not null pk autoincr INT(11)"`
	OrderAdjunctName    string    `json:"order_adjunct_name" xorm:"VARCHAR(128)"`
	OrderAdjunctUrl     string    `json:"order_adjunct_url" xorm:"VARCHAR(225)"`
	OrderAdjunctAtime   time.Time `json:"order_adjunct_atime" xorm:"DATETIME"`
	OrderAdjunctOrderId int       `json:"order_adjunct_order_id" xorm:"INT(11)"`
}
