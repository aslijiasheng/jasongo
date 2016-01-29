package model

import (
	"time"
)

type TcOpenApply struct {
	OpenApplyId                   int       `json:"open_apply_id" xorm:"not null pk autoincr INT(11)"`
	TcOpenApplyAccount            int       `json:"tc_open_apply_account" xorm:"INT(11)"`
	TcOpenApplyOrder              int       `json:"tc_open_apply_order" xorm:"INT(11)"`
	TcOpenApplyGoodsAcctCode      string    `json:"tc_open_apply_goods_acct_code" xorm:"VARCHAR(128)"`
	TcOpenApplyGoodsName          string    `json:"tc_open_apply_goods_name" xorm:"VARCHAR(128)"`
	TcOpenApplyGoodsCode          string    `json:"tc_open_apply_goods_code" xorm:"VARCHAR(128)"`
	OpenApplyProductBasicAcctCode string    `json:"open_apply_product_basic_acct_code" xorm:"VARCHAR(128)"`
	OpenApplyProductBasicName     string    `json:"open_apply_product_basic_name" xorm:"VARCHAR(128)"`
	OpenApplyProductBasicCode     string    `json:"open_apply_product_basic_code" xorm:"VARCHAR(128)"`
	OpenApplyApplyStatus          int       `json:"open_apply_apply_status" xorm:"INT(11)"`
	OpenApplyOwnerUser            int       `json:"open_apply_owner_user" xorm:"INT(11)"`
	OpenApplyDepartment           int       `json:"open_apply_department" xorm:"INT(11)"`
	OpenApplyCreateTime           time.Time `json:"open_apply_create_time" xorm:"DATETIME"`
	OpenApplyModifyTime           time.Time `json:"open_apply_modify_time" xorm:"DATETIME"`
}
