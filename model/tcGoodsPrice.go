package model

type TcGoodsPrice struct {
	GoodsPriceId            int    `json:"goods_price_id" xorm:"not null pk autoincr INT(11)"`
	GoodsPriceGoodsId       int    `json:"goods_price_goods_id" xorm:"not null INT(11)"`
	GoodsPriceCode          string `json:"goods_price_code" xorm:"not null VARCHAR(128)"`
	GoodsPriceVisable       int    `json:"goods_price_visable" xorm:"INT(11)"`
	GoodsPriceStartPrice    string `json:"goods_price_start_price" xorm:"DECIMAL(20,3)"`
	GoodsPriceCycleDays     int    `json:"goods_price_cycle_days" xorm:"INT(11)"`
	GoodsPriceCycleUnit     int    `json:"goods_price_cycle_unit" xorm:"INT(11)"`
	GoodsPriceCyclePrice    string `json:"goods_price_cycle_price" xorm:"DECIMAL(20,3)"`
	GoodsPriceEffectiveDays int    `json:"goods_price_effective_days" xorm:"INT(11)"`
}
