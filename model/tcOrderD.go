package model

import (
	"time"
)

type TcOrderD struct {
	OrderDId                       int       `json:"order_d_id" xorm:"not null pk autoincr INT(11)"`
	OrderDGoodsName                string    `json:"order_d_goods_name" xorm:"VARCHAR(128)"`
	OrderDGoodsCode                string    `json:"order_d_goods_code" xorm:"VARCHAR(128)"`
	OrderDProductBasicName         string    `json:"order_d_product_basic_name" xorm:"VARCHAR(128)"`
	OrderDProductBasicCode         string    `json:"order_d_product_basic_code" xorm:"VARCHAR(128)"`
	OrderDProductBasicServicecycle string    `json:"order_d_product_basic_servicecycle" xorm:"VARCHAR(128)"`
	OrderDGoodsPrimecost           string    `json:"order_d_goods_primecost" xorm:"DECIMAL(20,2)"`
	OrderDGoodsDisc                string    `json:"order_d_goods_disc" xorm:"DECIMAL(20,2)"`
	OrderDProductBasicStyle        int       `json:"order_d_product_basic_style" xorm:"INT(11)"`
	OrderDOrderId                  int       `json:"order_d_order_id" xorm:"INT(11)"`
	OrderDProductBasicPrimecost    string    `json:"order_d_product_basic_primecost" xorm:"DECIMAL(20,2)"`
	OrderDProductBasicDisc         string    `json:"order_d_product_basic_disc" xorm:"DECIMAL(20,2)"`
	OrderDProductRate              int       `json:"order_d_product_rate" xorm:"INT(11)"`
	OrderDNumber                   string    `json:"order_d_number" xorm:"VARCHAR(225)"`
	OrderDList                     int       `json:"order_d_list" xorm:"INT(11)"`
	OrderDIdcCost                  string    `json:"order_d_idc_cost" xorm:"DECIMAL(20,2)"`
	OrderDSmsCost                  string    `json:"order_d_sms_cost" xorm:"DECIMAL(20,2)"`
	OrderDDomainCost               string    `json:"order_d_domain_cost" xorm:"DECIMAL(20,2)"`
	OrderDOpenStartdate            time.Time `json:"order_d_open_startdate" xorm:"DATETIME"`
	OrderDOpenEnddate              time.Time `json:"order_d_open_enddate" xorm:"DATETIME"`
	OrderDGoodsAcctCode            string    `json:"order_d_goods_acct_code" xorm:"VARCHAR(255)"`
	OrderDProductBasicAcctCode     string    `json:"order_d_product_basic_acct_code" xorm:"VARCHAR(255)"`
}
