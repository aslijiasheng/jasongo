package model

import (
	"time"
)

type TcGoods struct {
	GoodsId        int       `json:"goods_id" xorm:"not null pk autoincr INT(11)"`
	GoodsCode      string    `json:"goods_code" xorm:"not null VARCHAR(128)"`
	GoodsName      string    `json:"goods_name" xorm:"not null VARCHAR(128)"`
	GoodsType      int       `json:"goods_type" xorm:"not null INT(11)"`
	GoodsDesc      string    `json:"goods_desc" xorm:"not null VARCHAR(225)"`
	GoodsVisable   int       `json:"goods_visable" xorm:"not null INT(11)"`
	GoodsIsTrail   int       `json:"goods_is_trail" xorm:"not null INT(11)"`
	GoodsIsSale    int       `json:"goods_is_sale" xorm:"not null INT(11)"`
	GoodsSaleType  string    `json:"goods_sale_type" xorm:"not null VARCHAR(128)"`
	GoodsTrailDays int       `json:"goods_trail_days" xorm:"not null INT(11)"`
	GoodsCheckPay  int       `json:"goods_check_pay" xorm:"not null INT(11)"`
	GoodsIsNew     int       `json:"goods_is_new" xorm:"not null INT(11)"`
	GoodsUtime     time.Time `json:"goods_utime" xorm:"not null DATETIME"`
	GoodsPrice     string    `json:"goods_price" xorm:"DECIMAL(20,2)"`
}
