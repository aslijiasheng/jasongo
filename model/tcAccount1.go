package model

import (
	"time"
)

type TcAccount1 struct {
	AccountId              int       `json:"account_id" xorm:"not null pk autoincr INT(11)"`
	AccountName            string    `json:"account_name" xorm:"VARCHAR(128)"`
	AccountPath            string    `json:"account_path" xorm:"VARCHAR(128)"`
	AccountLeader          string    `json:"account_leader" xorm:"VARCHAR(128)"`
	AccountUpdateTime      time.Time `json:"account_update_time" xorm:"DATETIME"`
	AccountAccountShopexid string    `json:"account_account_shopexid" xorm:"VARCHAR(128)"`
	AccountCreateTime      time.Time `json:"account_create_time" xorm:"DATETIME"`
	AccountTelephone       string    `json:"account_telephone" xorm:"VARCHAR(50)"`
}
