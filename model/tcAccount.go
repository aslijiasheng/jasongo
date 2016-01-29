package model

import (
	"time"
)

type TcAccount struct {
	AccountId              int       `json:"account_id" xorm:"not null pk autoincr INT(11)"`
	AccountName            string    `json:"account_name" xorm:"VARCHAR(128)"`
	AccountLeader          string    `json:"account_leader" xorm:"VARCHAR(128)"`
	AccountUpdateTime      time.Time `json:"account_update_time" xorm:"DATETIME"`
	AccountAccountShopexid string    `json:"account_account_shopexid" xorm:"VARCHAR(128)"`
	AccountCreateTime      time.Time `json:"account_create_time" xorm:"DATETIME"`
	AccountTelephone       string    `json:"account_telephone" xorm:"VARCHAR(50)"`
	TypeId                 int       `json:"type_id" xorm:"INT(11)"`
	AccountEmail           string    `json:"account_email" xorm:"VARCHAR(50)"`
	AccountDepartment      int       `json:"account_department" xorm:"INT(11)"`
	AccountIsU8            int       `json:"account_is_u8" xorm:"INT(11)"`
	AccountPbiId           string    `json:"account_pbi_id" xorm:"VARCHAR(225)"`
	AccountNameU8          string    `json:"account_name_u8" xorm:"VARCHAR(225)"`
}
