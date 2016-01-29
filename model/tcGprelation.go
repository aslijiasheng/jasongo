package model

type TcGprelation struct {
	GprelationId             int `json:"gprelation_id" xorm:"not null pk autoincr INT(11)"`
	GprelationGoodsId        int `json:"gprelation_goods_id" xorm:"not null INT(11)"`
	GprelationProductTechId  int `json:"gprelation_product_tech_id" xorm:"not null INT(11)"`
	GprelationProductBasicId int `json:"gprelation_product_basic_id" xorm:"not null INT(11)"`
	GprelationRate           int `json:"gprelation_rate" xorm:"not null INT(11)"`
}
