package model

import (
	"time"
)

type TcAcctOpen struct {
	AcctOpenId                   int       `json:"acct_open_id" xorm:"not null pk autoincr INT(11)"`
	AcctOpenAccount              int       `json:"acct_open_account" xorm:"INT(11)"`
	AcctOpenGoodsAcctCode        string    `json:"acct_open_goods_acct_code" xorm:"VARCHAR(225)"`
	AcctOpenGoodsName            string    `json:"acct_open_goods_name" xorm:"VARCHAR(128)"`
	AcctOpenGoodsCode            string    `json:"acct_open_goods_code" xorm:"VARCHAR(128)"`
	AcctOpenProductBasicAcctCode string    `json:"acct_open_product_basic_acct_code" xorm:"VARCHAR(128)"`
	AcctOpenProductBasicName     string    `json:"acct_open_product_basic_name" xorm:"VARCHAR(128)"`
	AcctOpenProductBasicCode     string    `json:"acct_open_product_basic_code" xorm:"VARCHAR(128)"`
	AcctOpenServiceStartdate     time.Time `json:"acct_open_service_startdate" xorm:"DATETIME"`
	AcctOpenServiceEnddate       time.Time `json:"acct_open_service_enddate" xorm:"DATETIME"`
	AcctOpenIsInvalid            int       `json:"acct_open_is_invalid" xorm:"INT(11)"`
}
